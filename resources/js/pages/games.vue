<template>
  <div>
    <Modal
      v-model="descOfGameModal"
      :mask-closable="false"
      :closable="false"
      width="700px;"
    >
      <div class="row">
        <div class="col-md-6">
          <img style="max-height: 70vh" :src="pic" alt="" title="" />
        </div>
        <div class="col-md-6">
          <div style="max-height: 70vh; overflow: auto">
            <h2>{{ title }}</h2>
            <p>{{ descOfGameText }}</p>
          </div>
        </div>
      </div>

      <div slot="footer">
        <Button type="default" @click="descOfGameModal = false">Close</Button>
      </div>
    </Modal>
    <Modal
      v-model="addModal"
      title="Add a new game"
      :mask-closable="false"
      :closable="false"
    >
      <div class="row">
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Game Name</p>
            <Input
              type="text"
              v-model="form_data.title"
              placeholder="Game Name"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Description</p>
            <Input
              type="textarea"
              :rows="16"
              v-model="form_data.description"
              placeholder="Description"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Age Start</p>
            <Input
              type="number"
              v-model="form1.age_start"
              placeholder="Age Start"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Age End</p>
            <Input
              type="number"
              v-model="form1.age_end"
              placeholder="Age End"
            />
          </div>
        </div>

        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Cover Photo</p>
          </div>
          <template>
            <Upload
              ref="upload"
              type="drag"
              :with-credentials="true"
              :on-success="handleImageUpload"
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

            <Card span="10" offset="1">
              <div style="text-align: center">
                <img
                  style="width: 100%; height: auto"
                  :src="form_data.cover_photo ? form_data.cover_photo : ''"
                />
              </div>
            </Card>
          </template>
        </div>
      </div>

      <div slot="footer">
        <Button type="default" @click="addModal = false">Close</Button>
        <Button
          type="primary"
          :disabled="isSending"
          :loading="isSending"
          @click="addGame"
          >{{ isSending ? "Please wait..." : "Add game" }}</Button
        >
      </div>
    </Modal>
    <Modal
      v-model="editModal"
      :mask-closable="false"
      :closable="false"
      title="Edit game"
    >
      <div class="row">
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Game Name</p>
            <Input
              type="text"
              v-model="edit_data.title"
              placeholder="Game Name"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Description</p>
            <Input
              type="textarea"
              :rows="16"
              v-model="edit_data.description"
              placeholder="Description"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <p>Age Range (old): {{ edit_data.age_range }}</p>
          <div class="_3login_input_group">
            <p class="registration_title">Age Start</p>
            <Input
              type="number"
              v-model="edit1.age_start"
              placeholder="Age Start"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <p>Note: Old one will remain by default</p>
          <div class="_3login_input_group">
            <p class="registration_title">Age End</p>
            <Input
              type="number"
              v-model="edit1.age_end"
              placeholder="Age End"
            />
          </div>
        </div>

        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Cover Photo</p>
          </div>
          <template>
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

            <Card span="10" offset="1">
              <div style="text-align: center">
                <img
                  style="width: 100%; height: auto"
                  :src="edit_data.cover_photo ? edit_data.cover_photo : ''"
                />
              </div>
            </Card>
          </template>
        </div>
      </div>
      <div slot="footer">
        <Button type="default" @click="editModal = false">Close</Button>
        <Button
          type="primary"
          :disabled="isSending"
          :loading="isSending"
          @click="updateGame"
          >{{ isSending ? "Please wait..." : "Update game" }}</Button
        >
      </div>
    </Modal>

    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
          <div class="_1card_header _padd20">
            <p class="_1card_header_title">Games</p>
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
              <button class="_btn_sm" @click="addModal = true">
                <i class="fas fa-plus"></i> Add Game
              </button>
            </div>
          </div>
          <div class="_table_responsive">
            <template v-if="!loaderCycle">
              <table class="_table">
                <thead v-if="!nodata" class="bg-200 text-900">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Total<br />Questions</th>
                    <th>Age</th>
                    <th>Cover<br />Photo</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <template v-if="nodata">
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <p class="_1card_header_title">No Data Found</p>
                    </div>
                    <div class="col-md-4"></div>
                  </div>
                </template>
                <tbody>
                  <tr v-for="(item, index) in data" :key="index">
                    <td>
                      {{ item.id }}
                    </td>
                    <td>{{ item.title }}</td>
                    <td>
                      <span v-if="item.total_questions">{{
                        item.total_questions.total_questions
                      }}</span>
                    </td>
                    <td>{{ item.age_range }}</td>
                    <td><img :src="item.cover_photo" alt="" title="" /></td>
                    <td>
                      <button
                        class="_btn _btn_outline_dark _btn_sm"
                        @click="descOfGame(item, index)"
                      >
                        Open
                      </button>
                    </td>
                    <td>
                      <button
                        class="_btn _btn_outline_info _btn_sm"
                        @click="editGame(item, index)"
                      >
                        Edit
                      </button>
                      <button
                        class="_btn _btn_outline_danger _btn_sm"
                        @click="deleteGame(index, item)"
                      >
                        Delete
                      </button>
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
  data() {
    return {
      addModal: false,
      descOfGameModal: false,
      pic: "",
      title: "",
      editModal: false,
      editIndex: -1,
      pagination: {},
      page: 1,
      loaderCycle: true,
      nodata: false,
      data: [],

      form1: {
        age_start: 0,
        age_end: 0,
      },
      edit1: {
        age_start: 0,
        age_end: 0,
      },
      form_data: {
        title: "",
        description: "",
        cover_photo: "",
        age_range: "",
      },
      edit_data: {
        id: "",
        title: "",
        description: "",
        cover_photo: "",
        age_range: "",
      },
      descOfGameText: "",
      isSending: false,
      imgName: "",
      visible: false,
      textVisible: false,
      listMethod: false,
      //   crfObj: {
      //     "X-CSRF-TOKEN": document
      //     .querySelector('meta[name="csrf-token"]')
      //     .getAttribute("content")
      //   },

      picName: "",
    };
  },
  methods: {
    descOfGame(item, index) {
      this.descOfGameText = item.description;
      this.pic = item.cover_photo;
      this.title = item.title;
      this.descOfGameModal = true;
    },
    async pagination_result(e) {
      this.page = e;
      const res = await this.callApi("get", `showGame?page=${this.page}`);
      if (res.status == 200) {
        this.data = res.data.games.data;
        this.pagination = res.data.games;
      }
    },
    async handleImageUpload(res, file) {
      this.form_data.cover_photo = file.response.split("<")[0];
      console.log("test str");
      console.log("form data is, ", this.picName);
    },
    async handleImageUploadEdit(res, file) {
      if (res) {
        this.edit_data.cover_photo = file.response.split("<")[0];
      }
    },

    async deleteGame(index, item) {
      // console.log(id)
      if (!confirm("WARNING!!! Are you sure to delete this info?")) {
        return;
      }
      let ob = {
        id: this.data[index].id,
      };

      const response = await this.callApi("post", "deleteGame", ob);
      if (response.status == 200) {
        this.data.splice(index, 1);
        this.s("Game deleted");
      } else {
        console.log(response);
        this.swr();
      }
    },

    async updateGame() {
      this.isSending = true;

      if (this.edit_data.title.trim() == "") {
        this.isSending = false;
        return this.e("Game Name can not be empty!");
      }
      if (this.edit_data.description.trim() == "") {
        this.isSending = false;
        return this.e("Game Description  can not be empty!");
      }

      if (this.edit1.age_start != 0 && this.edit1.age_end != 0)
        this.edit_data.age_range =
          this.edit1.age_start + " - " + this.edit1.age_end;

      const response = await this.callApi("post", "editGame", this.edit_data);
      if (response.status === 200) {
        this.data[this.editIndex] = response.data.games;
        this.edit_data = {};
        this.editIndex = -1;
        this.editModal = false;
        this.s("Game Updated");
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
        console.log(response);
      }
      this.isSending = false;
    },
    editGame(item, index) {
      if (!confirm("WARNING!!! Are you sure to edit this info?")) {
        return;
      }
      this.edit_data = _.cloneDeep(item);
      this.editIndex = index;
      this.editModal = true;
    },

    async addGame() {
      this.isSending = true;

      if (this.form_data.title.trim() == "") {
        this.isSending = false;
        return this.e("Game Name can not be empty!");
      }
      if (this.form_data.description.trim() == "") {
        this.isSending = false;
        return this.e("Game Description  can not be empty!");
      }
      if (this.form1.age_start == 0) {
        this.isSending = false;
        return this.e("Age start is required!");
      }
      if (this.form1.age_end == 0) {
        this.isSending = false;
        return this.e("Age end is required!");
      }

      this.form_data.age_range =
        this.form1.age_start + " - " + this.form1.age_end;

      const response = await this.callApi("post", "addGame", this.form_data);
      if (response.status === 200) {
        location.reload();
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
      }
      this.isSending = false;
    },
  },
  //  async created(){

  //         const res = await this.callApi('get',`showGame`)
  //         	if( res.status != 200){
  //             this.loaderCycle = true
  //           }
  // 		if( res.status == 200){
  //       this.data = res.data.games.data
  //                 this.pagination = res.data.games
  //       this.loaderCycle = false
  //       if(this.data.length == 0) this.nodata = true
  // 		} else {
  // 			this.swr()
  // 		}
  // 		this.isLoading = false
  // 	},
};
</script>



