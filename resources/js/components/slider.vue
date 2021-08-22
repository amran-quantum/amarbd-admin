<template>
  <div>
    <!-- Side bar -->
    <div class="_1sidebar">
      <div class="_1sidebar_main _bg_default">
        <div class="logo">
          <template v-if="isLoggedIn">
            <router-link to="/">
              <img
                class="logo_img"
                src="assets/img/amarbd.jpg"
                alt=""
                title=""
              />
            </router-link>
          </template>
          <template v-else>
            <router-link to="/">
              <img
                class="logo_img"
                src="assets/img/amarbd.jpg"
                alt=""
                title=""
              />
            </router-link>
          </template>
        </div>

        <template v-if="isLoggedIn">
          <ul class="_navbar">
            <li>
              <router-link to="/dashboard">
                <div class="nav_item">
                  <div class="nav_item_icon">
                    <i class="fas fa-chart-line"></i>
                  </div>
                  <span class="nav_item_text">Dashboard</span>
                </div>
              </router-link>
            </li>

            <li>
              <router-link to="/users">
                <div class="nav_item">
                  <div class="nav_item_icon">
                    <i class="fas fa-users"></i>
                  </div>
                  <span class="nav_item_text">Users</span>
                </div>
              </router-link>
            </li>
            <li v-if="authUser.userType == 'admin'">
              <router-link to="/categories">
                <div class="nav_item">
                  <div class="nav_item_icon">
                    <i class="fas fa-database"></i>
                  </div>
                  <span class="nav_item_text">Categories</span>
                </div>
              </router-link>
            </li>
            <li>
              <router-link to="/news">
                <div class="nav_item">
                  <div class="nav_item_icon">
                    <i class="fas fa-book"></i>
                  </div>
                  <span class="nav_item_text">News</span>
                </div>
              </router-link>
            </li>
            <li>
                <router-link to="/about">
                <div class="nav_item">
                    <div class="nav_item_icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <span class="nav_item_text">About Us</span>
                </div>
                </router-link>
            </li>
            <!--
                <li>
                  <router-link to="/questions">
                    <div class="nav_item">
                        <div class="nav_item_icon">
                            <i class="fas fa-question"></i>
                        </div>
                        <span class="nav_item_text">Questions</span>
                    </div>
                  </router-link>
                </li>

                <li>
                  <router-link to="/contact">
                    <div class="nav_item">
                        <div class="nav_item_icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <span class="nav_item_text">Contact Us</span>
                    </div>
                  </router-link>
                </li>
                <li>
                  <router-link to="/privacy">
                    <div class="nav_item">
                        <div class="nav_item_icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <span class="nav_item_text">Privacy and Terms</span>
                    </div>
                  </router-link>
                </li>
                <li>
                  <router-link to="/notifications">
                    <div class="nav_item">
                        <div class="nav_item_icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="nav_item_text">Notifications</span>
                    </div>
                  </router-link>
                </li> -->
            <li>
              <router-link to="/profile">
                <div class="nav_item">
                  <div class="nav_item_icon">
                    <i class="fas fa-info"></i>
                  </div>
                  <span class="nav_item_text">Profile</span>
                </div>
              </router-link>
            </li>
          </ul>
        </template>
      </div>
    </div>
    <!-- Side bar -->
  </div>
</template>
