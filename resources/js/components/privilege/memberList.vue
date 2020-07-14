<template>
    <div class="my-template">
        <el-row class="filter" style="margin-bottom: 20px;">
            <el-col :span="4" class="title">
                在职状态
            </el-col>
            <el-col :span="8" class="title-content">
                <el-select v-model="search.enable" placeholder="请选择" style="width: 100%;">
                    <el-option
                        v-for="item in ['全部','在职','离职']"
                        :key="item"
                        :value="item"
                        :label="item"
                    >
                        {{ item }}
                    </el-option>
                </el-select>
            </el-col>
            <el-col :span="4" class="title">
                角色
            </el-col>
            <el-col :span="8" class="title-content">
                <el-select v-model="search.role_id" placeholder="请选择" style="width: 100%;">
                    <el-option
                        v-for="item in roles"
                        :key="item.id"
                        :value="item.id"
                        :label="item.name"
                    >
                        {{ item.name }}
                    </el-option>
                </el-select>
            </el-col>
            <el-col :span="24" class="el-col-clear"></el-col>
            <el-col :span="4" class="title">
                关键字
            </el-col>
            <el-col :span="8" class="title-content">
                <el-input v-model="search.keyword" placeholder="姓名、手机号、邮箱"></el-input>
            </el-col>
            <el-col :span="12" class="title-content" style="text-align: right;">
                <el-button type="primary" @click="dealSearch()">搜 索</el-button>
                <el-button type="primary" @click="handleAdd()">添 加</el-button>
            </el-col>
        </el-row>

        <el-table
            :data="list"
            border
            style="width: 100%">
            <el-table-column
                prop="name"
                label="姓名"
                width="100">
            </el-table-column>
            <el-table-column
                prop="email"
                label="邮箱"
                width="250"
                >
            </el-table-column>
            <el-table-column
                label="角色"
                width="250"
                >
                <template slot-scope="scope">
                    {{ scope.row.role_names.join('，') }}
                </template>
            </el-table-column>
            <el-table-column
                prop="phone"
                label="手机号"
                width="120"
                >
            </el-table-column>
            <el-table-column
                prop="enable"
                label="状态"
                width="150"
                >
            </el-table-column>

            <el-table-column
                label="操作"
                width="95">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        type="warning"
                        @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="block">
            <el-pagination
                @current-change="handleCurrentChange"
                :current-page.sync=currentPage
                :page-size=perPage
                layout="total ,prev, pager, next, jumper"
                :total=total
            >
            </el-pagination>
        </div>


        <!--  编辑框      -->
        <el-dialog title="用户信息" :visible.sync="dialogFormVisible">
            <privilege-member-info :roles="roles" :member="theMember"></privilege-member-info>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="save()">确 定</el-button>
            </div>
        </el-dialog>

    </div>

</template>

<script>
    export default {
        name: "memberList",
        data() {
            return {
                name: "测试",
                is_first_flag: true,
                theMember: null,
                total: 0,
                perPage: 0,
                currentPage: 0,
                loading: true,
                dialogFormVisible: false,
                list: [],
                roles: [],
                search: {
                    keyword: "",
                    enable: "全部",
                    role_id: ''
                }
            }
        },
        methods: {
            getList: function(page) {
                let api = 'graphql';
                let query = '';
                if ( this.is_first_flag ) {
                    query = `
                        query($page:Int,$search: PrivilegeSearch){
                          privilege( page: $page ,search: $search){
                            role_list{
                                id
                                name
                            }
                            user_list{
                              current_page
                              per_page
                              total
                              data {
                                id
                                name
                                email
                                phone
                                address
                                enable
                                role
                                role_names
                              }
                            }
                          }
                        }

                    `;

                } else {
                    query = `
                        query($page:Int,$search: PrivilegeSearch){
                          privilege( page: $page , search: $search ){
                            user_list{
                              current_page
                              per_page
                              total
                              data {
                                id
                                name
                                email
                                phone
                                address
                                enable
                                role
                                role_names
                              }
                            }
                          }
                        }

                    `;
                }

                let variables = {
                    page: page,
                    search: this.search
                };


                this.axios.post(api,{query:query,variables:variables}).then((response) => {
                    if( response.data.status == 0 ) {
                        let members = response.data.values.privilege.user_list;
                        this.list = members.data;
                        this.total = members.total;
                        this.perPage = members.per_page;
                        this.currentPage = members.current_page;

                        if ( this.is_first_flag ) {
                            this.roles = response.data.values.privilege.role_list;
                            this.roles.splice(0,0,{id:'',name:"全部"})
                        } else  {
                            this.is_first_flag = false;
                        }
                    } else {
                        swal("请求失败", response.data.message , "warning");
                    }
                }).catch(error => {
                    swal('系统错误','请联系技术部','error')
                })
            },
            handleEdit: function(index,obj) {
                this.dialogFormVisible = true;
                this.theMember = JSON.parse( JSON.stringify(obj) );
            },
            handleAdd : function() {
                this.dialogFormVisible = true;
                this.theMember = JSON.parse( JSON.stringify({
                    id:0,
                    name:'',
                    phone:'',
                    address:'',
                    email:'',
                    enable:'',
                    role:'',
                    role_names:[],
                }) );
            },
            handleCurrentChange: function (page) {
                this.getList(page)
            },
            dealSearch: function() {
                this.getList(1)
            },
            save: function () {
                let name = $("#add-name").val();
                let phone = $("#add-phone-number").val();
                let address = $("#add-address").val();
                let email = $("#add-email").val();
                let enable = $("#add-enable").val();
                let role = new Array;
                $('#add-role').find('input[type="checkbox"]:checked').each(function(){
                    role.push($(this).val());
                });

                let password = $("#add-password").val();

                if ( name == '' ) {
                    this.$message({
                        message: '名称不能为空',
                        type: 'warning'
                    });
                    return false;
                } else if ( phone == '' ) {
                    this.$message({
                        message: '手机号不能为空',
                        type: 'warning'
                    });
                    return false;
                } else if ( phone.length != 11 ) {
                    this.$message({
                        message: '手机号必须为11位',
                        type: 'warning'
                    });
                    return false;
                } else if ( address == '' ) {
                    this.$message({
                        message: '地址不能为空',
                        type: 'warning'
                    });
                    return false;
                } else if ( email == '' ) {
                    this.$message({
                        message: '邮箱不能为空',
                        type: 'warning'
                    });
                    return false;
                }



                let data = {
                    id: this.theMember.id,
                    name: name,
                    phone: phone,
                    address: address,
                    email: email,
                    enable: enable,
                    role: role.join(','),
                    password: password
                };

                let api = 'api/privilege/v1/member/change';
                this.axios.post(api,data).then((response) => {
                    if( response.data.status == 0 ) {
                        this.dialogFormVisible = false;
                        this.getList( this.currentPage );
                        swal('修改成功','','success');
                    } else {
                        swal("请求失败", response.data.message , "warning");
                    }
                }).catch(error => {
                    swal('系统错误','请联系技术部','error')
                })

            }
        },
        mounted() {
            this.getList(1)
        }
    }
</script>

<style scoped>
.block {
    margin-top: 20px;
    float: right;
}
.title {
    background-color: #f4f4f4;
    line-height: 40px;
    border-radius: 5px;
    text-align: center;
}
.title-content {
    line-height: 40px;
    display: inline-block;
    padding: 0 15px;
}
</style>
