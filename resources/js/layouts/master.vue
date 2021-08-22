<template>
  <div>
      
      <div class="full-item">
          <div class="sidebar-item">
              <sliderSection/>
          </div>
    
           
        

          <div class="_content">
               <headerSection/>
            <transition name="component-fade" mode="out-in">
                <router-view :key="$route.fullPath"></router-view>
            </transition>
          </div>
      </div>
    
    
   
    
  </div>


</template>


<script>
import headerSection from '../components/header.vue'
import sliderSection from '../components/slider.vue'

export default {
   components:{
      headerSection,
      sliderSection
        },
  created(){
     this.$store.commit('setAuthuser', (window.authUser));
  },
}
</script>