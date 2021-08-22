<template>
    <div>
      <div class="row">
		    <div class="col-12 col-md-12 col-lg-12">
                <div style="height:440px" class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
		            <div class="_1card_header _padd20">
			            <p class="_1card_header_title">Contact Us</p>
                        <quill-editor style="width: 100%;height:300px"
                        :content="text"
                        :options="editorOption"
                        @change="onEditorChange($event)"
                        ></quill-editor>
		                <button class="_btn _btn_outline_info _btn_sm" :disabled="isSending" :loading="isSending" @click="editPrivacyText">{{isSending ? 'Please wait...' : 'Update'}}</button>	
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
            text:'',
            isSending:false, 
            edit_data:{
                post_description: ''
            },
            data:[],
            editIndex: -1,
            editorOption: {
            // some quill options
           syntax:true,
           toolbar: [['code-block']]   
      },      
		}
	},
    methods:{
    onEditorChange({ quill, html, text }) {
      // console.log('editor change!', quill, html, text)
      this.text = html;
      // this.content = text
    },
    
    async editPrivacyText(){
      this.isSending = true
        this.editIndex = this.data[0].id
        this.edit_data.id = this.data[0].id
        this.edit_data.post_description = this.text
        const res = await this.callApi('post','editPrivacyText',this.edit_data)
        
        if(res.status == 200){
          
            this.data[this.editIndex] = res.data.privacy
            this.text = this.data[this.editIndex].post_description
            this.isSending = false
            this.s('Text updated successfully!')
            this.isSending = false
        }

    },    
},
 async created(){
  
   const res = await this.callApi('get',`showPrivacyText`)
        	if( res.status != 200){
            this.loaderCycle = true
          }
		if( res.status == 200){
      this.data = res.data.privacy
      this.text = this.data[0].post_description
      this.loaderCycle = false
      if(this.data.length == 0) this.nodata = true
		} else {
			this.swr()
		}
       
	}
}
</script>

