<template>
  <div>
    <Modal
      v-model="userDetailsModal"
      title="User Game History"
      :width="950"
      :mask-closable="false"
      :closable="false"
    >
      <table class="_table">
        <thead class="bg-200 text-900">
          <tr>
            <th>Profile Pic</th>
            <th>Address</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td><img :src="details.profilePic" alt="" title="" /></td>
            <td>{{details.address ? details.address : "NA"}}</td>
          </tr>
        </tbody>
      </table>
      <div slot="footer">
        <Button type="default" @click="userDetailsModal = false">Close</Button>
      </div>
    </Modal>
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
          <div class="_1card_header _padd20">
            <p class="_1card_header_title">Users</p>
            <template v-if="loaderCycle">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <p class="_1card_header_title">Loading...</p>
                </div>
                <div class="col-md-4"></div>
              </div>
            </template>

            <div class="_dashboard_actions">
              <div class="_dashboard_actions_user"></div>
            </div>
          </div>

          <div class="_table_responsive">
            <template v-if="nodata">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <p class="_1card_header_title">No Data Found</p>
                </div>
                <div class="col-md-4"></div>
              </div>
            </template>
            <template v-else>
              <table class="_table">
                <thead class="bg-200 text-900">
                  <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Details</th>
                    <!-- <th>Game<br />History</th> -->
                    <!-- <th>Gender</th>
                    <th>Birth Date</th> -->
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="(item, index) in data" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.fullName }}</td>
                    <td>{{ item.email }}</td>
                    <td>
                      <button
                        @click="userDetailsModalOn(item, index)"
                        class="_btn_outline_secondary _btn_sm"
                      >
                        Details
                      </button>
                    </td>
                    <!-- <td>{{ item.gender }}</td>
                    <td>
                      <span v-if="item.birth_date">{{ item.birth_date }}</span
                      ><span v-if="!item.birth_date">N/A</span>
                    </td> -->
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
  data() {
    return {
      userDetailsModal: false,
      loaderCycle: true,
      nodata: false,
      editIndex: -1,
      pagination: {},
      page: 1,
      details: {},
      data: [],
      location: [],
      schedule: [],
      form_data: {
        fullName: "",
        email: "",
        address: "",
        phone: "",
        location_id: "",
        schedule_id: "",
        password: "",
        password_confirmation: "",
      },
      edit_data: {
        fullName: "",
        email: "",
        address: "",
        phone: "",
        location_id: "",
        schedule_id: "",
      },
      search: "",
      fullName: "",
      isSending: false,
    };
  },
  methods: {
    userDetailsModalOn(item, index) {
      this.userDetailsModal = true;
      this.details = item;
    },
    async pagination_result(e) {
      this.page = e;
      const res = await this.callApi("get", `showUser?page=${this.page}`);
      if (res.status == 200) {
        this.data = res.data.user.data;
        this.pagination = res.data.user;
      }
    },

    async deleteUser(item, index) {
      // console.log(id)
      if (!confirm("WARNING!!! Are you sure to delete this info?")) {
        return;
      }
      let ob = {
        id: item.id,
      };

      const response = await this.callApi("post", "deleteUser", ob);
      if (response.status === 200) {
        this.data.splice(index, 1);
        this.s("User deleted");
      } else {
        console.log(response);
        this.swr();
      }
    },
  },
  async created() {
    const res = await this.callApi("get", `showUser`);
    if (res.status != 200) {
      this.loaderCycle = true;
    }

    if (res.status == 200) {
      this.loaderCycle = false;
      this.data = res.data.user.data;
      this.pagination = res.data.user;
      if (this.data.length == 0) {
        this.nodata = true;
      }
    } else {
      this.swr();
    }
  },
};
</script>
<style>
.loader {
  border: 8px solid #f3f3f3;
  border-radius: 50%;
  border-top: 8px solid #3498db;
  width: 50px;
  height: 50px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% {
    -webkit-transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>



