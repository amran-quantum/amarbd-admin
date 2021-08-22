<template>
<div>
      <Modal
        v-model="descOfQuestionModal"
        :mask-closable="false"
        :closable="false"
        width='700px;'
    >
    <h3 v-if="questionset.game">Game Name : {{questionset.game.title}}</h3>
    <hr>
    <h4>Question</h4>
    <p>{{questionset.question}}</p>
    <hr>
    <div v-for="(o, index) in questionset.options" class="col-6 col-md-6">
        <h4>Option {{index+1}}</h4>
        <p :key="index">{{o.optionName}}</p>   
        <hr>         
    </div>
    
    <h4>Right Answer</h4>
    <p>{{questionset.right_answer}}</p>
    
    

    <div slot="footer">
        <Button type="default" @click="descOfQuestionClose(questionset)">Close</Button>
    </div>
    </Modal>
    <Modal
        v-model="addModal"
        title="Add a new question"
        :mask-closable="false"
        :closable="false"
    >
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="_3login_input_group">
                <p class="registration_title">Game Name</p>
                <Select v-model="form_data.game_id" style="width:100%" size="large" >
                    <Option v-for="(item,index) in gamelist" :key="index" :value="item.id">{{item.title}}</Option>
                </Select>	
            </div>
        </div>
        
        <div class="col-12 col-md-12">
            <div class="_3login_input_group">
                <p class="registration_title">Question</p>
                <Input type="textarea" :rows="4" v-model="form_data.question" placeholder="Question"/>
            </div>
        </div>
        <div class="col-12 col-md-12" >
           <div class="row">
                <div v-for="(o, index) in form_data.options" class="col-6 col-md-6">
                    <div class="_1card  _0border">
                        <div  class="_1card_header _padd5">
                            <h4>Option {{index+1}}</h4>
                            
                        <div class="_dashboard_actions ">
                            <i @click="deleteOption(index)" class="fas fa-trash"></i>
                        </div>
                    </div>     
		        </div>
                    <Input type="textarea" :rows="4" v-model="o.optionName" placeholder="Write Here" :key="index"/>            
                </div>
           </div>
            <br>
            <button class="_btn_sm"  @click="addOption"><i class="fas fa-plus"></i> Add New Option</button>
           <br>
                    
           
        </div>
           

        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
                <br>
                <p class="registration_title">Right Answer</p>
                 <Select v-model="temp" style="width:100%" size="large">
                    <Option v-for="(item,index) in form_data.options" :key="index" :value="item.optionName">Option {{index+1}}</Option>
                </Select>		
            </div>
        </div>
        
    </div>

    <div slot="footer">
        <Button type="default" @click="addModal=false">Close</Button>
        <Button type="primary" :disabled="isSending" :loading="isSending" @click="addQuestion">{{isSending ? 'Please wait...' : 'Add question'}}</Button>
    </div>
    </Modal>
         <Modal
		   v-model="editModal"
			:mask-closable="false"
            :closable="false"
			title="Edit question"
		>
        <div class="row">
        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
                <p class="registration_title">Game Name</p>
                <Select v-model="edit_data.game_id" style="width:100%" size="large" >
                    <Option v-for="(item,index) in gamelist" :key="index" :value="item.id">{{item.title}}</Option>
                </Select>	
            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="_3login_input_group">
                <p class="registration_title">Question</p>
                <Input type="textarea" :rows="4" v-model="edit_data.question" placeholder="Question"/>
            </div>
        </div>
       
        <div class="col-12 col-md-12" >
           <div class="row">
                <div v-for="(o, index) in edit_data.options" class="col-6 col-md-6">
                    <div class="_1card  _0border">
                        <div  class="_1card_header _padd5">
                            <h4>Option {{index+1}}</h4>
                            
                        <div class="_dashboard_actions ">
                            <i @click="deleteOptionE(index)" class="fas fa-trash"></i>
                        </div>
                    </div>     
		        </div>
                    <Input type="textarea" :rows="4" v-model="o.optionName" placeholder="Write Here" :key="index"/>            
                </div>
           </div>
            <br>
            <button class="_btn_sm"  @click="addOptionE"><i class="fas fa-plus"></i> Add New Option</button>
           <br>
                    
           
        </div>
   

        <div class="col-6 col-md-6">
            <div class="_3login_input_group">
            <br>

                <p class="registration_title">Right Answer</p>
                 <Select v-model="temp2" style="width:100%" size="large">
                    <Option v-for="(item,index) in edit_data.options" :key="index" :value="item.optionName">Option {{index+1}}</Option>
                </Select>		
            </div>
        </div>
        


        
        
    </div>
         <div slot="footer">
                <Button type="default" @click="editModal=false">Close</Button>
                <Button type="primary" :disabled="isSending" :loading="isSending"  @click="updateQuestion">{{isSending ? 'Please wait...' : 'Update question'}}</Button>
            </div>
	
    	</Modal>


<div class="row">
		<div class="col-12 col-md-12 col-lg-12">
       <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
		<div  class="_1card_header _padd20">
			<p class="_1card_header_title">Questions</p>
                         <template v-if="loaderCycle">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">  <p class="_1card_header_title">Loading...</p></div>
                                <div class="col-md-4"></div>
                            </div>
                        </template>
			  <div class="_dashboard_actions ">
          <button class="_btn_sm"  @click="addModal = true"><i class="fas fa-plus"></i> Add question</button>
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
                    <th>Game<br>Name</th>
                    <th>Question</th>
                    <th>Description</th>
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
                      <span v-if="item.game">{{item.game.title}}</span>
                    </td>
                    <td> {{item.question}}</td>
                    <td> <button class="_btn _btn_outline_dark _btn_sm" @click="descOfQuestion(item,index)">Open</button> </td>
                    <td>
                      <button class="_btn _btn_outline_info _btn_sm" @click="editQuestion(item,index)">Edit</button> 
                      <button class="_btn _btn_outline_danger _btn_sm" @click="deleteQuestion(index)">Delete</button>
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
            editModal: false,
            descOfQuestionModal: false,
            editIndex:-1,
            pagination:{},
            page:1,
            loaderCycle:true,
            nodata:false,
            data:[],
            form_data: {
               game_id: '',
               question: '',
               options: [{optionName : ''}],
               right_answer: '',     
            },  
            temp:'',
            temp2:'',
            gamelist:[],
            edit_data: {
               id:'',
               game_id: '',
               question: '',
               options: [{optionName : ''}],
               right_answer: '',
            },
            questionset:{},
            isSending: false,   
		}
	},
    methods:{
       async pagination_result(e){

            this.page = e
            const res = await this.callApi('get',`showQuestion?page=${this.page}`)
            if(res.status == 200){
                this.data = res.data.questions.data
                this.pagination = res.data.questions
               
            }
        },
  
    descOfQuestion(item,index){
        this.descOfQuestionModal = true
        this.questionset= item
        this.questionset.options = JSON.parse(item.options)
    },
    descOfQuestionClose(item){
        this.descOfQuestionModal = false
        this.questionset.options = JSON.stringify(item.options)
    },
    addOption(){
        this.form_data.options.push({ optionName: '' })
        this.p.options.push({ optionName: '' })
    },
    deleteOption(find,index){
        this.form_data.options.splice(index,1)
        this.p.options.splice(index,1)
    },
    addOptionE(){
        this.edit_data.options.push({ optionName: '' })
    },
    deleteOptionE(find,index){
        this.edit_data.options.splice(index,1)
    },
    
       async deleteQuestion(index){
            // console.log(id)
            if(!confirm("WARNING!!! Are you sure to delete this info?")){
                return;
            }
            let ob = {
                id:this.data[index].id
            }

            const response = await this.callApi('post', 'deleteQuestion',ob)
            if (response.status === 200) {
                this.data.splice(index,1)
                this.s('Question deleted');

            }
            else if(response.status === 402){
              return this.e(response.data.msg)
            }
            else{
                console.log(response);
                this.swr()
            }
        },
        	
		 async updateQuestion () {
        this.isSending = true
		
            if (this.edit_data.question.trim() =='') {
              this.isSending = false
               return this.e('Question can not be empty!')
            }
            if (this.temp2 =='') {
              this.isSending = false
               return this.e('Right Answer can not be empty!')
            }
           
            
            
            if(this.edit_data.options.length>0){
                
                this.edit_data.options = JSON.stringify(this.edit_data.options)
                this.edit_data.right_answer = this.temp2
                
            }
			const response = await this.callApi('post', 'editQuestion', this.edit_data)
            if (response.status === 200) {
                location.reload()
				this.data[this.editIndex] = response.data.questions
				this.edit_data ={}
				this.editIndex = -1
				this.editModal = false
				this.s('Question Updated')
            }
            else if(response.status ==422){
                this.e(response.data.msg)
            }
            else{
                this.swr();
                console.log(response);
            }
             this.isSending = false
        },
          editQuestion(item,index){
			if(!confirm("WARNING!!! Are you sure to edit this info?")){
                return;
            }
            this.edit_data.id = item.id
            this.edit_data.game_id = item.game_id
            this.edit_data.question = item.question
            this.edit_data.right_answer = item.right_answer
            this.edit_data.options = item.options
            this.edit_data.options = JSON.parse(this.edit_data.options)
            this.editIndex = index
			this.editModal = true
        },

       
        async addQuestion(){
            
           
          
          this.isSending = true
          
            if (this.form_data.game_id =='') {
              this.isSending = false
               return this.e('Game Name can not be empty!')
            }
            if (this.form_data.question.trim() =='') {
              this.isSending = false
               return this.e('Question can not be empty!')
            }
          
            if(this.temp ==''){
              this.isSending = false
               return this.e('Right Answer  can not be empty!')
            }
           
            if(this.form_data.options.length>0){
                
                this.form_data.options = JSON.stringify(this.form_data.options)
                this.form_data.right_answer = this.temp
                
            }
            const response = await this.callApi('post', 'addQuestion', this.form_data)
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
            this.callApi('get',`showQuestion`),
            this.callApi('get',`showGameApi`)
        ])
        if(res2.status == 200){
            this.gamelist = res2.data.games
        }
        if( res.status != 200){
            this.loaderCycle = true
        }
        
		if( res.status == 200){
            this.data = res.data.questions.data
            this.pagination = res.data.questions
            this.loaderCycle = false
            if(this.data.length == 0) this.nodata = true
		}else {
			this.swr()
		}
		this.isLoading = false
	},
}
</script>



