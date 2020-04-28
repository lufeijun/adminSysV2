<template>
    <div>
        <div class="col-lg-21 text-right" style="margin-bottom: 15px;">
            <el-button type="primary" @click="handleAdd()">添 加</el-button>
        </div>
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
                >
            </el-table-column>
            <el-table-column
                label="角色"
                >
                <template slot-scope="scope">
                    {{ scope.row.roles.join('，') }}
                </template>
            </el-table-column>
            <el-table-column
                prop="phone"
                label="手机号"
                >
            </el-table-column>
            <el-table-column
                prop="enable"
                label="状态"
                >
            </el-table-column>

            <el-table-column label="操作">
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
                layout="prev, pager, next, jumper"
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
                theMember: null,
                total: 0,
                perPage: 0,
                currentPage: 0,
                loading: true,
                dialogFormVisible: false,
                list: [],
                roles: []
            }
        },
        methods: {
            getList: function(page) {
                let api = 'api/privilege/v1/member/list';
                this.axios.post(api,{page:page}).then((response) => {
                    if( response.data.status == 0 ) {
                        let members = response.data.values.members;
                        this.list = members.data;
                        this.total = members.total;
                        this.perPage = members.per_page;
                        this.currentPage = members.from;

                        this.roles = response.data.values.roles;
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
                    roles:[],
                }) );
            },
            handleCurrentChange: function (page) {
                this.getList(page)
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
</style>
