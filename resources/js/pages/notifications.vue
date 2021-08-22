<template>
<div>
     
    <Modal
        v-model="addModal"
        title="Add a new question"
        :mask-closable="false"
        :closable="false"
    >
    <div class="row">
        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
                <p class="registration_title">User Name</p>
                <Select v-model="form_data.user_id" style="width:100%" size="large" >
                    <Option v-for="(item,index) in userlist" :key="index" :value="item.id">{{item.name}}</Option>
                </Select>	
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
                <p class="registration_title">Text</p>
                <Input type="textarea" v-model="form_data.msg" placeholder="Text"/>
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
                <p class="registration_title">Notification Type</p>
                <Select v-model="form_data.type" style="width:100%" size="large" >
                    <Option value="push">Push</Option>
                    <Option value="email">Email</Option>
                    <Option value="new courses">New Courses</Option>
                </Select>	
            </div>
        </div>
      
       
        
    </div>

    <div slot="footer">
        <Button type="default" @click="addModal=false">Close</Button>
        <Button type="primary" :disabled="isSending" :loading="isSending" @click="addNotification">{{isSending ? 'Please wait...' : 'Add notification'}}</Button>
    </div>
    </Modal>
         


<div class="row">
		<div class="col-12 col-md-12 col-lg-12">
       <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
		<div  class="_1card_header _padd20">
			<p class="_1card_header_title">Notifications</p>
      <template v-if="loaderCycle">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">  <p class="_1card_header_title">Loading...</p></div>
                                <div class="col-md-4"></div>
                            </div>
                        </template>
			  <div class="_dashboard_actions ">
          <button class="_btn_sm"  @click="addModal = true"><i class="fas fa-plus"></i> Add notification</button>
        </div>
                    
		</div>
         <div class="_table_responsive">
            <template v-if="!loaderCycle">
              <table class="_table">
                <thead v-if="!nodata" class="bg-200 text-900">
                  <tr>
                    <th>
                      ID
                    </th>
                    <th>User<br>Name</th>
                    <th>Text</th>
                    <th>Seen</th>
                    <th>Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <template v-if="nodata">
                <div class="row">
                  
                    <div class="col-md-4"></div>
                    <div class="col-md-4"> <p class="_1card_header_title">No Data Found</p>  </div>
                    <div class="col-md-4"></div>
                  
                </div>
                        
                       
            </template>
                <tbody>
                  <tr v-for="(item,index) in data" :key="index">
                    <td>
                      {{item.id}}
                    </td>
                    <td>
                      <span v-if="item.user">{{item.user.name}}</span>
                    </td>
                    <td> {{item.msg}}</td>
                    <td> {{item.seen}}</td>
                    <td> {{item.type}}</td>
                   <td>
                     <button class="_btn _btn_outline_danger _btn_sm" @click="deleteNotification(index)">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </template> 
             <!-- Pagination -->
                                                 
                        <div class="pagin">
                            <Page 
                        :current="1"
                        :total="pagination.total" 
                        @on-change="pagination_result" 
                        :page-size="10" 
                        />
                        </div>
              <!-- Pagination -->           
            </div>
          </div>
        </div>
		  </div>
		</div>


		  
		
    
</template>

<script>
export default {
    data(){
		return{
            addModal:false,
            pagination:{},
            page:1,
            
            loaderCycle:true,
            nodata:false,
            data:[],
            form_data: {
               user_id: '',
               msg: '',   
               type: '',   
            },
            userlist:[],
            isSending: false,   
		}
	},
    methods:{
       async pagination_result(e){

            this.page = e
            const res = await this.callApi('get',`showNotification?page=${this.page}`)
            if(res.status == 200){
                this.data = res.data.notifications.data
                this.pagination = res.data.notifications
               
            }
        },
  
   
       async deleteNotification(index){
            // console.log(id)
            if(!confirm("WARNING!!! Are you sure to delete this info?")){
                return;
            }
            let ob = {
                id:this.data[index].id
            }

            const response = await this.callApi('post', 'deleteNotification',ob)
            if (response.status === 200) {
                this.data.splice(index,1)
                this.s('Notification deleted');

            }
            else if(response.status === 402){
              return this.e(response.data.msg)
            }
            else{
                console.log(response);
                this.swr()
            }
        },
        	
		

       
        async addNotification(){
          this.isSending = true
          
            if (this.form_data.user_id =='') {
              this.isSending = false
               return this.e('User Name can not be empty!')
            }
            if (this.form_data.msg.trim() =='') {
              this.isSending = false
               return this.e('Text can not be empty!')
            }
            if (this.form_data.type =='') {
              this.isSending = false
               return this.e('Notification type not be empty!')
            }
          
            const response = await this.callApi('post', 'addNotification', this.form_data)
            if (response.status === 200) {
               location.reload()
            
            }
            else if(response.status ==422){
                this.e(response.data.msg)
            }else{
                this.swr()
            }
             this.isSending = false

        }  
},
 async created(){
   
        const [res,res2] = await Promise.all([
            this.callApi('get',`showNotification`),
            this.callApi('get',`showUserApi`)
        ])
        if(res2.status == 200){
            this.userlist = res2.data.users
        }
        if( res.status != 200){
            this.loaderCycle = true
        }
        
		if( res.status == 200){
            this.data = res.data.notifications.data
            this.pagination = res.data.notifications
            this.loaderCycle = false
            if(this.data.length == 0) this.nodata = true
		}else {
			this.swr()
		}
		this.isLoading = false
	},
}
</script>



