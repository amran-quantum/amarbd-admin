<template>
  <div>
    <div class="_1menu _d_flex justify-content-between _bg_default">
      <!-- <div class="_1menu_search">
                <p class="_1menu_search_icon"><i class="fas fa-search"></i></p>
                <input type="text" placeholder="Serach">
              </div> -->

      <div></div>

      <div class="_1menu_list">
        <ul>
          <li>
            <template v-if="isLoggedIn">
              <li class="nav-item">
                <!-- <p @click="logout"> Logout</p>  -->
                <p v-if="authUser.userType == 'journalist'">Journalist</p>
                <p v-if="authUser.userType == 'admin'">Owner</p>
                <a href="/logout" class="nav-link">Logout</a>
              </li>
            </template>
            <template v-else>
              <router-link to="/login">Login </router-link>
            </template>
          </li>

          <!-- <li>
                  <div class="profile">
                   <img src="assets/img/mobile2.png" alt="" title="">
                  </div>

                  <div class="profile_drop">
                    <div class="profile_drop_mian">
                      <p class="profile_drop_title"><i class="fas fa-crown"></i> Go Pro</p>

                      <ul class="profile_drop_list">
                        <li><a href="">Set status</a></li>
                        <li><a href="">Profile & account</a></li>
                        <li><a href="">Feedback</a></li>
                      </ul>

                      <ul class="profile_drop_list">
                        <li><a href="">Setting</a></li>
                        <li>
                              <template v-if="isLoggedIn">
                                <li class="nav-item">
                                    <button @click="logout"> Logout</button>


                                </li>
                                <li class="nav-item nav-link">
                                    <router-link :to="`/user/${authUser.id}`" class="nav-link">
                                    {{authUser.fullName}}
                                </router-link>
                                </li>
                              </template>


                        </li>
                      </ul>
                    </div>
                  </div>
                </li> -->
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    async logout() {
      const res = await this.callApi("get", `logout`);
      if (res.status == 200) {
        this.$router.push("/login");
      }
    },
  },
  async created() {},
};
</script>
