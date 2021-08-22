<template>
  <div>
    <Modal
      v-model="addModal"
      title="Add a new Category"
      :mask-closable="false"
      :closable="false"
    >
      <div class="row">
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Category Name</p>
            <Input
              type="text"
              v-model="form_data.name"
              placeholder="Category Name"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Category Name</p>
            <Input
              type="text"
              v-model="form_data.bn_name"
              placeholder="Bangla Category Name"
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
          @click="addCategory"
          >{{ isSending ? "Please wait..." : "Add Category" }}</Button
        >
      </div>
    </Modal>
    <Modal
      v-model="editModal"
      :mask-closable="false"
      :closable="false"
      title="Edit Category"
    >
      <div class="row">
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Category Name</p>
            <Input
              type="text"
              v-model="edit_data.name"
              placeholder="Category Name"
            />
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div class="_3login_input_group">
            <p class="registration_title">Bangla Category Name</p>
            <Input
              type="text"
              v-model="edit_data.bn_name"
              placeholder="Bangla Category Name"
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
          @click="updateCategory"
          >{{ isSending ? "Please wait..." : "Update Category" }}</Button
        >
      </div>
    </Modal>

    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="_1card _mar_b15 _b_radious7 _5box_shadow _0border">
          <div class="_1card_header _padd20">
            <p class="_1card_header_title">All Categories</p>
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
                <i class="fas fa-plus"></i> Add Category
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
                    <th>বাংলা নাম</th>
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.bn_name }}</td>
                    <td>
                      <button
                        class="_btn _btn_outline_info _btn_sm"
                        @click="editCategory(item, index)"
                      >
                        Edit
                      </button>
                      <button
                        class="_btn _btn_outline_danger _btn_sm"
                        @click="deleteCategory(index, item)"
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
                :page-size="20"
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
      descOfCategoryModal: false,
      pic: "",
      title: "",
      editModal: false,
      editIndex: -1,
      pagination: {},
      page: 1,
      loaderCycle: true,
      nodata: false,
      data: [],

      form_data: {
        name: "",
        bn_name: ""
      },
      edit_data: {
        id: "",
        name: "",
        bn_name: ""
      },
      isSending: false,
      visible: false,
      textVisible: false,
      listMethod: false,
    };
  },
  methods: {
    async pagination_result(e) {
      this.page = e;
      const res = await this.callApi("get", `showCategory?page=${this.page}`);
      if (res.status == 200) {
        this.data = res.data.category.data;
        this.pagination = res.data.category;
      }
    },

    async deleteCategory(index, item) {
      if (!confirm("WARNING!!! Are you sure to delete this info?")) {
        return;
      }
      let ob = {
        id: this.data[index].id,
      };

      const response = await this.callApi("post", "deleteCategory", ob);
      if (response.status == 200) {
        this.data.splice(index, 1);
        this.s("Category deleted");
      } else {
        this.swr();
      }
    },

    async updateCategory() {
      this.isSending = true;

      if (this.edit_data.name.trim() == "") {
        this.isSending = false;
        return this.e("Category Name can not be empty!");
      }
      if (this.edit_data.bn_name.trim() == "") {
        this.isSending = false;
        return this.e("Bangla Category Name can not be empty!");
      }

      const response = await this.callApi(
        "post",
        "editCategory",
        this.edit_data
      );
      if (response.status === 200) {
        this.data[this.editIndex] = response.data.category;
        this.edit_data = {};
        this.editIndex = -1;
        this.editModal = false;
        this.s("Category Updated");
      } else if (response.status == 422) {
        this.e(response.data.msg);
      } else {
        this.swr();
        console.log(response);
      }
      this.isSending = false;
    },
    editCategory(item, index) {
      if (!confirm("WARNING!!! Are you sure to edit this info?")) {
        return;
      }
      this.edit_data = _.cloneDeep(item);
      this.editIndex = index;
      this.editModal = true;
    },

    async addCategory() {
      this.isSending = true;

      if (this.form_data.name.trim() == "") {
        this.isSending = false;
        return this.e("Category Name can not be empty!");
      }

      if (this.form_data.bn_name.trim() == "") {
        this.isSending = false;
        return this.e("Bangla Category Name can not be empty!");
      }

      const response = await this.callApi(
        "post",
        "addCategory",
        this.form_data
      );
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
  async created() {
    if (authUser.userType == "journalist") {
      this.$router.push("/dashboard");
    } else {
      const res = await this.callApi("get", `showCategory`);
      if (res.status != 200) {
        this.loaderCycle = true;
      }
      if (res.status == 200) {
        this.data = res.data.category.data;
        this.pagination = res.data.category;
        this.loaderCycle = false;
        if (this.data.length == 0) this.nodata = true;
      } else {
        this.swr();
      }
      this.isLoading = false;
    }
  },
};
</script>



