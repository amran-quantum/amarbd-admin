<template>
    <div>
        <div class="_login">
         <template>
             <div>
                <Form v-model="form_data">
                    <div class="_1input_group">
                        <p class="_1label">Email</p>
                        <Input class="_1input" type="text" v-model="form_data.email" placeholder="Enter Email"/>
                    </div>

                    <div class="_1input_group">
                        <p class="_1label">Password</p>
                        <Input type="password" v-model="form_data.password" placeholder="Enter Password"/>
                    </div>

                    <div class="_1input_button _text_center">
                        <button @click="login" class="_btn _btn_info " :class="isLoading==true? '_btn_loader' : ''" type="button">{{isLoading ? 'Logging in...' : 'Login'}}</button>
                    </div>
                </Form>
            </div>
         </template>
         
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                isLoading: false,
                user:{},
                form_data: {
                email:'',
                password:'',
                }
            }
        },
        methods:{
            async login(){
                
                if(this.form_data.email.trim()=='' || this.form_data.password.trim()==''){
                    
                    this.i("Email & Password con't be empty!");
                    return;
                }
                
                const res = await this.callApi('post','login',this.form_data)
                
                if(res.status===200 && res.data.user.userType == 'Admin'){
                    let d = res.data.user
                     this.$store.commit('setAuthuser', (d));
                     this.isLoading = true
                     
                    this.s("login successfull !")
                      // window.location = '/'

                    this.$router.push('/dashboard')
                
                }
                else if (res.status===422){
                    this.e(res.data.msg);
                }
                
                else {
                    this.isLoading = false
                    
                    this.e('Incorrect email or password');
                }
            },
        },
        created(){
            console.log(window.authUser)
            
        }
    }
</script>
