<template>
    <div>
        <div class="col-lg-21 text-right" style="margin-bottom: 15px;">
            <el-button type="primary" @click="handleAdd()">添 加</el-button>
        </div>
        <el-table
            :data="roles"
            border
            style="width: 100%">
            <el-table-column
                prop="id"
                label="ID"
                width="100">
            </el-table-column>
            <el-table-column
                prop="name"
                label="名称"
                >
            </el-table-column>

            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        type="warning"
                        @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                    <el-link type="primary" v-bind:href="'/admin/privilege/role/permit/show/'+scope.row.id" :underline="false" style="margin-left: 15px;">权限</el-link>
                </template>
            </el-table-column>
        </el-table>

        <!--  编辑框      -->
        <el-dialog title="角色信息" :visible.sync="dialogFormVisible">
            <div class="row">
                <div class="col-lg-12">
                    <input type="text" id="input-name" class="form-control" v-model="theRole.name"  placeholder="请填写角色名称">
                </div>
            </div>
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
                theRole: {
                    id:0,
                    name:''
                },
                loading: true,
                dialogFormVisible: false,
                roles: []
            }
        },
        methods: {
            getList: function() {
                let api = 'api/privilege/v1/role/list';
                this.axios.post(api).then((response) => {
                    if( response.data.status == 0 ) {
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
                this.theRole = JSON.parse( JSON.stringify(obj) );
            },
            handleAdd : function() {
                this.dialogFormVisible = true;
                this.theRole = JSON.parse( JSON.stringify({
                    id:0,
                    name:''
                }) );
            },
            save: function () {
                let name = $("#input-name").val();

                if ( name == '' ) {
                    this.$message({
                        message: '名称不能为空',
                        type: 'warning'
                    });
                    return false;
                }



                let data = {
                    id: this.theRole.id,
                    name: name,
                };

                let api = 'api/privilege/v1/role/change';
                this.axios.post(api,data).then((response) => {
                    if( response.data.status == 0 ) {
                        this.dialogFormVisible = false;
                        this.getList();
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
            this.getList()
        }
    }
</script>

<style scoped>
.block {
    margin-top: 20px;
    float: right;
}
</style>
