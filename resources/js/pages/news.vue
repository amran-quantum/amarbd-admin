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
          <img
            style="max-height: 70vh"
            :src="descOfGameText.banner"
            alt=""
            title=""
          />
        </div>
        <div class="col-md-6">
          <div style="max-height: 70vh; overflow: auto">
            <h2>{{ descOfGameText.headline }}</h2>
            <h3>
              Reprted by:
              {{ descOfGameText.user ? descOfGameText.user.fullName : "" }}
            </h3>
            at
            {{ descOfGameText.created_at }}
            <!-- <p>{{ descOfGameText }}</p> -->
            <div
              contenteditable="true"
              v-html="descOfGameText.news"
              @input="onDivInput($event)"
            ></div>
          </div>
          <div style="max-height: 70vh; overflow: auto">
            <h2>{{ descOfGameText.bn_headline }}</h2>
            <div
              contenteditable="true"
              v-html="descOfGameText.bn_news"
              @input="onDivInput2($event)"
            ></div>
          </div>
        </div>
      </div>

      <div slot="footer">
        <Button type="default" @click="descOfGameModal = false">Close</Button>
      </div>
    </Modal>
    <Modal
      v-model="addModal"
      title="Add a new news"
      :mask-closable="false"
      :closable="false"
    >
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Headline</p>
            <Input
              type="text"
              v-model="form_data.headline"
              placeholder="Headline"
            />
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Headline</p>
            <Input
              type="text"
              v-model="form_data.bn_headline"
              placeholder="Headline"
            />
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">News</p>
            <quill-editor
              style="width: 100%; height: 300px"
              :content="text"
              :options="editorOption"
              @change="onEditorChange($event)"
            ></quill-editor>
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla News</p>
            <quill-editor
              style="width: 100%; height: 300px"
              :content="text3"
              :options="editorOption"
              @change="onEditorChange3($event)"
            ></quill-editor>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Division</p>
            <Select
              v-model="form_data.division"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaDivision"
            >
              <Option
                v-for="(item, index) in divisions"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Division</p>
            <Select
              v-model="form_data.bn_division"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishDivision"
            >
              <Option
                v-for="(item, index) in divisions"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">District</p>
            <Select
              v-model="form_data.district"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaDistrict"
            >
              <Option
                v-for="(item, index) in districts"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla District</p>
            <Select
              v-model="form_data.bn_district"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishDistrict"
            >
              <Option
                v-for="(item, index) in districts"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Category</p>
            <Select
              v-model="form_data.category"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaCategory"
            >
              <Option
                v-for="(item, index) in category"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Category</p>
            <Select
              v-model="form_data.bn_category"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishCategory"
            >
              <Option
                v-for="(item, index) in category"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
          </div>
        </div>

        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Banner</p>
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
                  :src="form_data.banner ? form_data.banner : ''"
                />
              </div>
            </Card>
          </template>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Video Link</p>
            <Input
              type="text"
              v-model="form_data.video_link"
              placeholder="Video Link"
            />
          </div>
        </div>
      </div>

      <div slot="footer">
        <Button type="default" @click="addModal = false">Close</Button>
        <Button
          type="primary"
          :disabled="isSending"
          :loading="isSending"
          @click="addNews"
          >{{ isSending ? "Please wait..." : "Add News" }}</Button
        >
      </div>
    </Modal>
    <Modal
      v-model="editModal"
      :mask-closable="false"
      :closable="false"
      title="Edit News"
    >
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Headline</p>
            <Input
              type="text"
              v-model="edit_data.headline"
              placeholder="Headline"
            />
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Headline</p>
            <Input
              type="text"
              v-model="edit_data.bn_headline"
              placeholder="Headline"
            />
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">News</p>
            <quill-editor
              style="width: 100%; height: 300px"
              :content="text2"
              :options="editorOption"
              @change="onEditorChange2($event)"
            ></quill-editor>
          </div>
        </div>
        <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla News</p>
            <quill-editor
              style="width: 100%; height: 300px"
              :content="text4"
              :options="editorOption"
              @change="onEditorChange4($event)"
            ></quill-editor>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Division</p>
            <Select
              v-model="edit_data.division"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaDivisionEdit"
            >
              <Option
                v-for="(item, index) in divisions"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Division</p>
            <Select
              v-model="edit_data.bn_division"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishDivisionEdit"
            >
              <Option
                v-for="(item, index) in divisions"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">District</p>
            <Select
              v-model="edit_data.district"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaDistrictEdit"
            >
              <Option
                v-for="(item, index) in districts"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla District</p>
            <Select
              v-model="edit_data.bn_district"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishDistrictEdit"
            >
              <Option
                v-for="(item, index) in districts"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Category</p>
            <Select
              v-model="edit_data.category"
              style="width: 100%"
              size="large"
              @on-change="selectBanglaCategoryEdit"
            >
              <Option
                v-for="(item, index) in category"
                :key="index"
                :value="item.name"
                >{{ item.name }}</Option
              >
            </Select>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Category</p>
            <Select
              v-model="edit_data.bn_category"
              style="width: 100%"
              size="large"
              @on-change="selectEnglishCategoryEdit"
            >
              <Option
                v-for="(item, index) in category"
                :key="index"
                :value="item.bn_name"
                >{{ item.bn_name }}</Option
              >
            </Select>
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
                  :src="edit_data.banner ? edit_data.banner : ''"
                />
              </div>
            </Card>
          </template>
        </div>
         <div class="col-12 col-md-12">
          <div class="_3login_input_group">
            <p class="registration_title">Video Link</p>
            <Input
              type="text"
              v-model="edit_data.video_link"
              placeholder="Video Link"
            />
          </div>
        </div>
      </div>
      <div slot="footer">
        <Button type="default" @click="editModal = false">Close</Button>
        <Button
          type="primary"
          :disabled="isSending"
          :loading="isSending"
          @click="updateNews"
          >{{ isSending ? "Please wait..." : "Update News" }}</Button
        >
      </div>
    </Modal>

    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
          <div class="_1card_header _padd20">
            <p class="_1card_header_title">News</p>
             <Select
              style="width: 40%"
              v-model="publish_status"
              @on-change="sort_by_publish_status"
            >
              <Option value="1">Published</Option>
              <Option value="0">Not Published</Option>
            </Select>
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
                <i class="fas fa-plus"></i> Add News
              </button>
            </div>
          </div>
          <div class="_table_responsive">
            <template v-if="!loaderCycle">
              <table class="_table">
                <thead v-if="!nodata" class="bg-200 text-900">
                  <tr>
                    <th>ID</th>
                    <th>Head Line</th>
                    <th>Banner</th>
                    <th>Reporter</th>
                    <!--<th>View</th>!-->
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
                    <td>{{ item.headline }}</td>
                    <td><img :src="item.banner" alt="" title="" /></td>
                    <td>{{ item.user ? item.user.fullName : "" }}</td>
                    <!--<td>
                      <button
                        class="_btn _btn_outline_dark _btn_sm"
                        @click="descOfGame(item, index)"
                      >
                        Open
                      </button>
                    </td>-->
                    <td>
                      <span v-if="item.publish_status==0">
                        <button
                        class="_btn _btn_outline_success _btn_sm"
                        @click="approveNews(item, index)"
                         >
                        Approve
                      </button>
                      </span>
                      <button
                        class="_btn _btn_outline_info _btn_sm"
                        @click="editNews(item, index)"
                      >
                        Edit
                      </button>
                      <button
                        class="_btn _btn_outline_danger _btn_sm"
                        @click="deleteNews(index, item)"
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
      publish_status: "",
      pic: "",
      title: "",
      text: "",
      text2: "",
      text3: "",
      text4: "",
      editModal: false,
      editIndex: -1,
      pagination: {},
      page: 1,
      loaderCycle: true,
      nodata: false,
      data: [],
      category: [],

      form1: {
        age_start: 0,
        age_end: 0,
      },
      edit1: {
        age_start: 0,
        age_end: 0,
      },
      form_data: {
        headline: "",
        news: "",
        district: "",
        division: "",
        category: "",
        bn_headline: "",
        bn_news: "",
        bn_district: "",
        bn_division: "",
        bn_category: "",
        banner: "",
        video_link: "",
        user_id: "",
        publish_status: 0,
      },
      edit_data: {},
      descOfGameText: {},
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
      editorOption: {
        // some quill options
        syntax: true,
        toolbar: [["code-block"]],
      },
    };
  },
  methods: {
    descOfGame(item, index) {
      this.descOfGameText = item;
      this.descOfGameModal = true;
    },
    onEditorChange({ quill, html, text }) {
      this.text = html;
    },
    onEditorChange2({ quill, html, text }) {
      this.text2 = html;
    },
    onEditorChange3({ quill, html, text }) {
      this.text3 = html;
    },
    onEditorChange4({ quill, html, text }) {
      this.text4 = html;
    },
    async pagination_result(e) {
      this.page = e;
      const res = await this.callApi("get", `showNews?page=${this.page}`);
      if (res.status == 200) {
        this.data = res.data.news.data;
        this.pagination = res.data.news;
      }
    },
    async handleImageUpload(res, file) {
      this.form_data.banner = file.response.split("<")[0];
    },
    async handleImageUploadEdit(res, file) {
      if (res) {
        this.edit_data.banner = file.response.split("<")[0];
      }
    },
    selectBanglaDivision(){
          let isMatch = (element) => element.name == this.form_data.division;
          this.form_data.bn_division = this.divisions[this.divisions.findIndex(isMatch)].bn_name;
    },
    selectEnglishDivision(){
          let isMatch = (element) => element.bn_name == this.form_data.bn_division;
          this.form_data.division = this.divisions[this.divisions.findIndex(isMatch)].name;
    },
    selectBanglaDistrict(){
          let isMatch = (element) => element.name == this.form_data.district;
          this.form_data.bn_district = this.districts[this.districts.findIndex(isMatch)].bn_name;
    },
    selectEnglishDistrict(){
          let isMatch = (element) => element.bn_name == this.form_data.bn_district;
          this.form_data.district = this.districts[this.districts.findIndex(isMatch)].name;
    },
    selectBanglaCategory(){
          let isMatch = (element) => element.name == this.form_data.category;
          this.form_data.bn_category = this.category[this.category.findIndex(isMatch)].bn_name;
    },
    selectEnglishCategory(){
          let isMatch = (element) => element.bn_name == this.form_data.bn_category;
          this.form_data.category = this.category[this.category.findIndex(isMatch)].name;
    },
    selectBanglaDivisionEdit(){
          let isMatch = (element) => element.name == this.edit_data.division;
          this.edit_data.bn_division = this.divisions[this.divisions.findIndex(isMatch)].bn_name;
    },
    selectEnglishDivisionEdit(){
          let isMatch = (element) => element.bn_name == this.edit_data.bn_division;
          this.edit_data.division = this.divisions[this.divisions.findIndex(isMatch)].name;
    },
    selectBanglaDistrictEdit(){
          let isMatch = (element) => element.name == this.edit_data.district;
          this.edit_data.bn_district = this.districts[this.districts.findIndex(isMatch)].bn_name;
    },
    selectEnglishDistrictEdit(){
          let isMatch = (element) => element.bn_name == this.edit_data.bn_district;
          this.edit_data.district = this.districts[this.districts.findIndex(isMatch)].name;
    },
    selectBanglaCategoryEdit(){
          let isMatch = (element) => element.name == this.edit_data.category;
          this.edit_data.bn_category = this.category[this.category.findIndex(isMatch)].bn_name;
    },
    selectEnglishCategoryEdit(){
          let isMatch = (element) => element.bn_name == this.edit_data.bn_category;
          this.edit_data.category = this.category[this.category.findIndex(isMatch)].name;
    },

    async deleteNews(index, item) {
      // console.log(id)
      if (!confirm("WARNING!!! Are you sure to delete this info?")) {
        return;
      }
      let ob = {
        id: this.data[index].id,
      };

      const response = await this.callApi("post", "deleteNews", ob);
      if (response.status == 200) {
        this.data.splice(index, 1);
        this.s("News deleted");
      } else {
        console.log(response);
        this.swr();
      }
    },
    async approveNews(item,index){
      this.editIndex = index;
      const response = await this.callApi("post", "approveNews", {id:item.id});
      if (response.status === 200) {
        this.data[this.editIndex] = response.data.news;
        this.edit_data = {};
        this.editIndex = -1;
        this.editModal = false;
        this.s("News Updated");
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
        console.log(response);
      }
      this.isSending = false;
    },

    async updateNews() {
      this.isSending = true;

      if (this.edit_data.headline == "") {
        this.isSending = false;
        return this.e("Headline can not be empty!");
      }
      if (this.text2 == "") {
        this.isSending = false;
        return this.e("News can not be empty!");
      }
      if (this.edit_data.category == "") {
        this.isSending = false;
        return this.e("Category can not be empty!");
      }
      this.edit_data.news = this.text2;
      if (this.edit_data.bn_headline == "") {
        this.isSending = false;
        return this.e("Bangla Headline can not be empty!");
      }
      if (this.text4 == "") {
        this.isSending = false;
        return this.e("Bangla News can not be empty!");
      }
      if (this.edit_data.bn_category == "") {
        this.isSending = false;
        return this.e("Category can not be empty!");
      }
      this.edit_data.bn_news = this.text4;
      this.edit_data.user_id = this.authUser.id;
      const response = await this.callApi("post", "editNews", this.edit_data);
      if (response.status === 200) {
        this.data[this.editIndex] = response.data.news;
        this.edit_data = {};
        this.editIndex = -1;
        this.editModal = false;
        this.s("News Updated");
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
        console.log(response);
      }
      this.isSending = false;
    },
    editNews(item, index) {
      if (!confirm("WARNING!!! Are you sure to edit this info?")) {
        return;
      }
      this.text2 = item.news; /*  */
      this.text4 = item.bn_news; /*  */
      this.edit_data = _.cloneDeep(item);
      this.editIndex = index;
      this.editModal = true;
    },

    async addNews() {
      this.isSending = true;

      if (this.form_data.headline == "") {
        this.isSending = false;
        return this.e("Headline can not be empty!");
      }
      if (this.text == "") {
        this.isSending = false;
        return this.e("News can not be empty!");
      }
      if (this.form_data.category == "") {
        this.isSending = false;
        return this.e("Category can not be empty!");
      }
      this.form_data.news = this.text;
      if (this.form_data.bn_headline == "") {
        this.isSending = false;
        return this.e("Bangla Headline can not be empty!");
      }
      if (this.text3 == "") {
        this.isSending = false;
        return this.e("Bangla News can not be empty!");
      }
      if (this.form_data.bn_category == "") {
        this.isSending = false;
        return this.e("Bangla Category can not be empty!");
      }
      this.form_data.bn_news = this.text3;
      this.form_data.user_id = this.authUser.id;

      const response = await this.callApi("post", "addNews", this.form_data);
      if (response.status === 200) {
        location.reload();
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
      }
      this.isSending = false;
    },
    async sort_by_publish_status(){
      const res = await this.callApi("get", `showNews?user_id=${this.authUser.id}&publish_status=${this.publish_status}`);
      if (res.status == 200) {
        this.data = res.data.news.data;
        this.pagination = res.data.news;
      }
  }
  },
  async created() {
    const [res, res2] = await Promise.all([
      this.callApi("get", `showNews?user_id=${this.authUser.id}`),
      this.callApi("get", `api/showCategory`),
    ]);

    if (res.status != 200) {
      this.loaderCycle = true;
    }
    if (res.status == 200 && res2.status == 200) {
      this.category = res2.data.category;
      this.data = res.data.news.data;
      this.pagination = res.data.news;
      this.loaderCycle = false;
      if (this.data.length == 0) this.nodata = true;
    } else {
      this.swr();
    }
    this.isLoading = false;
  },
};
</script>



