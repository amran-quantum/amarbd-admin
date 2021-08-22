<template>
    <div class="_content">
        <div>
           <p>Welcome to Admin Panel</p>
        </div>
    </div>
</template>

<script>
export default {
    data(){
		return{
            
           
		}
	},
    methods:{
        },
		  
     
 async created(){
     if(this.isLoggedIn==false){
         this.$router.push('/dashboard')

     }
        
	},
}
</script>