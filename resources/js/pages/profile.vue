<template>
    <div>
        <Modal
            v-model="edit_profile_modal"
            title="Profile Edit"
            :width="850"
            :mask-closable="false"
            :closable="false"
        >
            <div class="row">
                <div class="col-6 col-md-6">
                    <div class="_3login_input_group">
                        <p class="registration_title">Full Name</p>
                        <Input
                            type="text"
                            v-model="user_data.fullName"
                            placeholder="Full Name"
                        />
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    <div class="_3login_input_group">
                        <p class="registration_title">Email</p>
                        <Input
                            type="text"
                            v-model="user_data.email"
                            placeholder="Email"
                        />
                    </div>
                </div>
                <div class="col-12 col-md-612">
                    <div class="_3login_input_group">
                        <p class="registration_title">Address</p>
                        <Input
                            type="text"
                            v-model="user_data.address"
                            placeholder="Address"
                        />
                    </div>
                </div>

                <div class="col-6 col-md-6">
                    <div class="_3login_input_group">
                        <p class="registration_title">Profile Photo</p>
                        <Upload
                            ref="upload"
                            type="drag"
                            :with-credentials="true"
                            :on-success="handleImageUploadEdit"
                            name="img"
                            action="/app/upload_image"
                        >
                            <div style="padding: 20px 0">
                                <Icon
                                    type="ios-cloud-upload"
                                    size="52"
                                    style="color: #3399ff"
                                ></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                    </div>

                </div>
                <div class="col-6 col-md-6">
                    <template>


                        <Card span="10" offset="1">
                            <div style="text-align: center">
                                <img
                                    style="width: 100%; height: auto"
                                    :src="
                                        user_data.profilePic ? user_data.profilePic : ''
                                    "
                                />
                            </div>
                        </Card>
                    </template>

                </div>
            </div>
            <div slot="footer">
                <Button type="default" @click="edit_profile_modal = false"
                    >Close</Button
                >
                <Button
                    type="primary"
                    :disabled="isSending"
                    :loading="isSending"
                    @click="updateProfile"
                    >{{
                        isSending ? "Please wait..." : "Update Profile"
                    }}</Button
                >
            </div>
        </Modal>
        <Modal
            v-model="password_change_modal"
            title="Password Change"
            :width="500"
            :mask-closable="false"
            :closable="false"
        >
            <template>
                <div>
                    <Form v-model="form_data">
                        <div class="_1input_group">
                            <p class="_1label">Current Password</p>
                            <Input
                                type="password"
                                v-model="user_data.fullName"
                                placeholder="Enter Current Password"
                            />
                        </div>
                        <div class="_1input_group">
                            <p class="_1label">New Password</p>
                            <Input
                                type="password"
                                v-model="form_data.new_password"
                                placeholder="Enter Current Password"
                            />
                        </div>
                        <div class="_1input_group">
                            <p class="_1label">Rewrite New Password</p>
                            <Input
                                type="password"
                                v-model="form_data.new_password2"
                                placeholder="Enter Current Password"
                            />
                        </div>

                        <div class="_1input_button _text_center">
                            <button
                                @click="changePassword"
                                class="_btn _btn_info "
                                :class="isLoading == true ? '_btn_loader' : ''"
                                type="button"
                            >
                                {{ isLoading ? "Updating..." : "Update" }}
                            </button>
                        </div>
                    </Form>
                </div>
            </template>
            <div slot="footer">
                <Button type="default" @click="password_change_modal = false"
                    >Close</Button
                >
            </div>
        </Modal>
        <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
            <div class="_table_responsive">
                <table class="_table">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>Full Name</th>
                            <th>Profile Pic</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Change Password</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ authUser.fullName }}</td>
                            <td>
                                <img
                                    :src="authUser.profilePic"
                                    alt=""
                                    title=""
                                />
                            </td>
                            <td>{{ authUser.email }}</td>
                            <td>{{ authUser.userType }}</td>
                            <td>
                                <button
                                    class="_btn _btn_outline_dark _btn_sm"
                                    @click="password_change_modal = true"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="_table">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>Address</th>
                            <th>Edit Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{
                                    authUser.address ? authUser.address : "N/A"
                                }}
                            </td>
                            <td>
                                <button
                                    class="_btn _btn_outline_dark _btn_sm"
                                    @click="editProfile"
                                >
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            password_change_modal: false,
            edit_profile_modal: false,
            isLoading: false,
             isSending: false,
            user: {},
            form_data: {
                current_password: "",
                new_password: "",
                new_password2: ""
            },
            user_data: {
                id:"",
                fullName: "",
                profilePic: "",
                email: "",
                address: ""
            }
        };
    },
    methods: {
        async handleImageUploadEdit(res, file) {
            if (res) {
                this.user_data.profilePic = file.response.split("<")[0];
            }
        },

        editProfile(){
            this.edit_profile_modal = true
            this.user_data.id = this.authUser.id;
            this.user_data.fullName = this.authUser.fullName;
            this.user_data.profilePic = this.authUser.profilePic;
            this.user_data.address = this.authUser.address;
            this.user_data.email = this.authUser.email;
        },
        async updateProfile() {
            const response = await this.callApi(
                "post",
                "editProfile",
                this.user_data
            );
            if (response.status === 200) {
                this.edit_profile_modal = false;
                this.$store.commit('setAuthuser', response.data.user);
                this.s("Profile Updated");
            } else if (response.status == 422) {
                this.e(response.data.msg);
            } else {
                this.swr();
            }
        },
        async changePassword() {
            this.isLoading = true;

            if (
                this.form_data.current_password.trim() == "" ||
                this.form_data.new_password.trim() == "" ||
                this.form_data.new_password2.trim() == ""
            ) {
                this.isLoading = false;
                this.i("Password con't be empty!");
                return;
            }

            const res = await this.callApi(
                "post",
                "changePassword",
                this.form_data
            );

            if (res.status === 200) {
                this.s("Password changed successfully!");
                this.$router.push("/dashboard");
                this.isLoading = false;
            } else {
                this.e(res.data.msg);
                this.isLoading = false;
            }
        }
    },
    created() {}
};
</script>
