<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use GraphApp\AppContext;
use GraphApp\AppObjectType;
use GraphApp\Routes;

use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Error\DebugFlag;
use GraphQL\Validator\Rules\DisableIntrospection;
use GraphQL\Validator\DocumentValidator;

class GraphQLController extends Controller
{
    private $exceptionsNotThrow = [
        \GraphApp\Exceptions\ApiResponseException::class,
        'graphql'
    ];


    //
    public function fire(Request $request)
    {
        if(@PUBLIC_PATH !== public_path()) {
            define('PUBLIC_PATH', public_path());
        }
        if (@BASE_PATH !== base_path()) {
            define('BASE_PATH', base_path());
        }
        if (@APP_DIRECTORY !== base_path('graph_app')) {
            define('APP_DIRECTORY', base_path('graph_app'));
        }
        if (@GRAPHQL_IN_LARAVEL_RUNTIME !== true) {
            define('GRAPHQL_IN_LARAVEL_RUNTIME', true);
        }

        if (env('APP_ENV') == 'server') {
            DocumentValidator::addRule(new DisableIntrospection());
        }

        \DB::connection()->enableQueryLog();

        $debug = false;
        if ( env('APP_ENV') != 'server') {
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
        }
        $returnMessage = '';

        $appContext = new AppContext();
        $appContext->rootUrl = $request->fullUrl();
        $appContext->request = $_REQUEST;

        $data = $request->all();
        $data += ['query' => null, 'variables' => null];
        $output = [];
        $returnStatus = 0;
        $returnMessage = 'success';

        try {
            $schema = new Schema([
                'query' => AppObjectType::query(Routes::queries()),
                // 'mutation' => AppObjectType::mutation(Routes::mutations()),
            ]);

            $result = GraphQL::executeQuery(
                $schema,
                $data['query'],
                null,
                $appContext,
                (array) $data['variables']
            );

            $shuldOutErrorMsg = false;

            $errorHandler = function(array $errors, callable $formatter) use ($debug,&$shuldOutErrorMsg) {
                $error = $errors[0];

                // 如果 debug = flase ，表示为线上环境，直接抛出异常，
                if ( get_class( $error ) == \GraphQL\Error\Error::class ) {
                    $shuldOutErrorMsg = true;
                    // error 是 GraphQL\Error\Error ，但是这个错误是由于业务部分代码触发的，所以需要获取 getPrevious( 异常链中的前一个异常 )
                    if ( $error->getPrevious() ) {
                        if ( $debug ) {
                            $error = $error->getPrevious();
                        } else {
                            throw $error->getPrevious();
                        }
                    }
                }

                return FormattedError::createFromException($error, $debug);
            };
            $resultArray = $result->setErrorsHandler($errorHandler)->toArray($debug);


            if ( array_key_exists('data', $resultArray) )
            {
                $output = $resultArray['data'];
            }
            if ( array_key_exists('errors', $resultArray) )
            {
                $output['errors'] = $shuldOutErrorMsg ? $resultArray['errors'] : "";
                $returnStatus = 4;
                if(isset($resultArray['errors']['debugMessage'])){
                    $returnMessage = $resultArray['errors']['debugMessage'];
                } else {
                    $returnMessage = 'Error';
                }
            }

        } catch (\Exception $e) {
		        if ( in_array( get_class( $e ) , $this->exceptionsNotThrow )  ) {
				        $returnStatus = 4;
				        $returnMessage = $e->getMessage();
		        } else if ( ! $debug ) {
			          throw $e;
		        }
	
        } catch (\Error $e) {
            // 如果 debug = flase ，表示为线上环境，直接抛出异常，
            if ( ! $debug ) {
                throw $e;
            } else {
		            $returnStatus = 4;
		            $errorMsg = $e->getMessage();
		            $returnMessage = $debug ? $errorMsg : 'Error';
            }
        }


        if ($debug) {
            $output['queries'] = \DB::getQueryLog();
            // $output['session_lifetime'] = \Redis::ttl('laravel:'.session()->getId());
        }
	
	
		    if ( env('APP_ENV') == 'server') {
			      unset( $output['__schema'] );
		    }
	
	
	
		    if (array_key_exists('__schema', $output)) {
				    $returnArray = ['status' => $returnStatus, 'message'=>$returnMessage ,'values' => $output];
				    $returnArray['data'] = $output;
				
				    return response()->json($returnArray, 200, [], JSON_UNESCAPED_UNICODE);
		    } else {
			      return $this->apiResponse($returnStatus, $returnMessage, $output);
		    }
        

        return $this->apiResponse($returnStatus, $returnMessage, $output);
    }

}
