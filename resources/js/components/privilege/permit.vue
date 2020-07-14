<template>
    <div class="vue-contain">
        <div class="row">
            <div class="col-lg-12 title">
                <h2>{{ name }}权限设置：</h2>
            </div>
            <div class="col-lg-6">
                <h3>菜单权限部分：</h3>
                <el-tree
                    :data="menu"
                    show-checkbox
                    default-expand-all
                    node-key="key"
                    ref="menu"
                    :default-checked-keys=ability.menu
                    :props="defaultProps">
                </el-tree>
            </div>

            <div class="col-lg-6">
                <h3>功能权限部分：</h3>
                <el-tree
                    :data="action"
                    show-checkbox
                    default-expand-all
                    node-key="key"
                    ref="action"
                    :default-checked-keys=ability.action
                    :props="defaultProps">
                </el-tree>
            </div>
        </div>
        <div class="buttons col-lg-12">
            <el-button type="primary" @click="save" class="save">保 存</el-button>
        </div>
    </div>

</template>

<script>
    export default {
        name: "memberList",
        props: ['id'],
        data() {
            return {
                name: '',
                menu: [],
                action: [],
                ability:{
                    action: [],
                    menu: []
                },
                defaultProps: {
                    children: 'children',
                    label: 'label'
                }
            }
        },
        methods: {
            getList: function() {
                // let api = 'api/privilege/v1/role/permit/get/' + this.id;
                let api = 'graphql';
                let query = `
                query($id: Int){
                      privilege ( id: $id ){
                        ability_action_tree
                        ability_menu_tree
                        role_msg{
                            id
                            name
                            ability_action
                            ability_menu
                        }
                      }
                    }
                `;
                let variables = {
                    id: this.id
                };
                this.axios.post(api,{query:query,variables:variables}).then((response) => {
                    if( response.data.status == 0 ) {
                        this.menu = JSON.parse( response.data.values.privilege.ability_menu_tree );
                        this.action = JSON.parse( response.data.values.privilege.ability_action_tree );
                        this.ability.action = response.data.values.privilege.role_msg.ability_action;
                        this.ability.menu = response.data.values.privilege.role_msg.ability_menu;
                        this.name = response.data.values.privilege.role_msg.name;
                    } else {
                        swal("请求失败", response.data.message , "warning");
                    }
                }).catch(error => {
                    swal('系统错误','请联系技术部','error')
                })
            },
            save: function() {

                let data = {
                    id:this.id,
                    menu: this.$refs.menu.getCheckedKeys(true),
                    action: this.$refs.action.getCheckedKeys(true)
                };

                let api = 'api/privilege/v1/role/permit/update/' + this.id;
                this.axios.post(api,data).then((response) => {
                    if( response.data.status == 0 ) {
                        swal({
                                title: "修改成功",
                                type: "success"
                            },function(isConfirm){
                                window.location.href="/admin/privilege/role/list";
                            });
                    } else {
                        swal("请求失败", response.data.message , "warning");
                    }
                }).catch(error => {
                    swal('系统错误','请联系技术部','error')
                });
            }
        },
        mounted() {
            this.getList()
        }
    }
</script>

<style scoped>
.vue-contain {
    padding: 20px;
    background-color: #ffffff;
}

.col-lg-12 {
    margin: 20px auto;
}
.title {
    border-bottom: 3px solid #ccc;
}
.save {
    width: 100%;
}
</style>
