<template>
  <div class="my-template">
    <div class="row">
        <div class="col-lg-6">姓名</div>
        <div class="col-lg-6">手机号</div>
        <div class="col-lg-6"><input type="text" class="form-control" id="add-name" placeholder="请填写真实姓名" v-model="member.name"></div>
        <div class="col-lg-6"><input type="number" class="form-control" id="add-phone-number" placeholder="请填写手机号码" v-model="member.phone"></div>
        <div class="col-lg-12" style="height: 15px;"></div>
        <div class="col-lg-12">住址</div>
        <div class="col-lg-12"><input type="text" class="form-control" id="add-address" placeholder="请填写住址" v-model="member.address"></div>
        <div class="col-lg-12" style="height: 15px;"></div>
        <div class="col-lg-6">邮箱／登录名</div>
        <div class="col-lg-4">密码(默认为：{{ defaultPassword }})</div>
        <div class="col-lg-2">状态</div>
        <div class="col-lg-6"><input type="text" class="form-control" id="add-email" placeholder="请填个人公司邮箱" v-model="member.email" style="width: 60%; display: inline-block;"></div>
        <div class="col-lg-4"><input type="text" class="form-control" id="add-password" v-model="member.pwd"></div>
        <div class="col-lg-2">
            <el-select v-model="member.enable" placeholder="请选择" id="add-enable">
                <el-option
                    v-for="enable in enables"
                    :key="enable"
                    :label="enable"
                    :value="enable">
                </el-option>
            </el-select>
        </div>
        <div class="col-lg-12" style="margin-top: 15px;">
            角色 请选择该成员担任的角色
        </div>
        <div class="col-lg-12" id="add-role" style="margin: 10px 0;">
            <el-checkbox-group v-model="member.role_names">
                <div class="col-lg-2" v-for="(role,index) in roles" style="display: inline;">
                    <label class="checkbox-inline">
                        <el-checkbox v-bind:role-name="role.name" :label="role.name" v-bind:role-id="role.id" class="role-class" >{{ role.name }}</el-checkbox>
                    </label>
                </div>
            </el-checkbox-group>
        </div>
        <div class="col-xs-12 next-password" style="height: 15px;"></div>
    </div>

  </div>
</template>

<script>
    export default {
        name: "memberInfo",
        props: ['roles','member'],
        data() {
            return {
                defaultPassword: "123456789",
                enables : [
                    '离职',
                    '在职'
                ]
            }
        }
    }
</script>

<style scoped>

</style>
