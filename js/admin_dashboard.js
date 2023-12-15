new Vue({
  el: '#app',
  data() {
    return {
      username: '',
      currentTab: 'inventory',
      snackInventory: [],
      snackrecord:[],
      userrecord:[],
      currentPage: 1,
      currentPage2: 1,
      pageSize: 6,
      pageSize2: 6,
      addStockForm: {
        snack_name: '',
        snack_quantity: 0,
        snack_price: 0,
      },
      addUserForm: {
        username: '',
        password: '',
        role: 0,
        member: 0,
      },
      // // 新增一个变量用于标记当前是否显示编辑按钮
      // showEditButtons: false,
      // // 用于保存当前选中的用户数据
      // selectedUser: null
    };
  },
  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const username = urlParams.get('username');
    console.log('username',username);
    if (username) {
        this.username = username;
    }
    this.fetchSnackInventory();
  },
  methods: {
    logout() {
      window.location.href = 'logout.php';
    },
    showInventory() {
      this.currentPage = 1;
      this.currentPage2 = 1;
      this.pageSize = 6;
      this.pageSize2 = 6;
      this.currentTab = 'inventory';
      this.fetchSnackInventory(); 
    },

    showAddStock() {
      this.currentTab = 'addStock';
    },
    
    showCheckrecord() {
      this.currentPage = 1;
      this.currentPage2 = 1;
      this.pageSize = 6;
      this.pageSize2 = 6;
      this.currentTab = 'checkrecord';
      this.fetchSnackRecord(); 
    },
// 用户信息管理
    showuserboard() {
      this.currentPage = 1;
      this.currentPage2 = 1;
      this.pageSize = 6;
      this.pageSize2 = 6;
      this.currentTab = 'userboard';
      this.manageuserinformation(); 
    },

    handleSizeChange(val) {
      console.log(`每页 ${val} 条`);
      this.pageSize = val
    },
    // 当前第几页
    handleCurrentChange(val) {
      console.log(`当前页: ${val}`);
      this.currentPage = val
    },
    handleSizeChange2(val) {
      console.log(`每页 ${val} 条`);
      this.pageSize2 = val
    },
    // 当前第几页
    handleCurrentChange2(val) {
      console.log(`当前页: ${val}`);
      this.currentPage2 = val
    },
    addStock() {
      let that = this;
      let formData = new FormData();
      formData.append('snack_name', that.addStockForm.snack_name);
      formData.append('snack_quantity', that.addStockForm.snack_quantity);
      formData.append('snack_price', that.addStockForm.snack_price);
    
      // axios.post('http://192.168.1.107/snack_system/admin_add_snack.php', formData)
      axios.post('http://localhost/snack_system/admin_add_snack.php', formData)
        .then(response => {
          console.log(response.data);
          that.fetchSnackInventory();
          that.$message({
            showClose: true,
            message: '添加成功',
            type: 'success'
          });
        })
        .catch(error => {
          console.error('Error adding stock:', error);
          that.$message({
            showClose: true,
            message: '添加失败，请检查输入',
            type: 'error'
          });
        });
    },
    // 添加用户
    addUser() {
      let that = this;
      let formData = new FormData();
      formData.append('username', that.addUserForm.username);
      formData.append('password', that.addUserForm.password);
      formData.append('role', that.addUserForm.role);
      formData.append('member', that.addUserForm.member);

      // axios.post('http://192.168.1.107/snack_system/admin_add_user.php', formData)
      axios.post('http://localhost/snack_system/admin_add_user.php', formData)
        .then(response => {
          console.log(response.data);
          that.manageuserinformation();  // 重新查询一次，用于刷新表格
          that.$message({
            showClose: true,
            message: '添加成功',
            type: 'success'
          });
        })
        .catch(error => {
          console.error('Error adding user:', error);
          that.$message({
            showClose: true,
            message: '添加失败，请检查输入',
            type: 'error'
          });
        });
    },
    // 删除用户
    deleteUser(userId) {
      // 实现删除具有指定userId的用户
      let that = this;
      let formData = new FormData();
      formData.append('userId', userId);
      // axios.delete(`/api/users/${userId}`)
      axios.post('http://localhost/snack_system/admin_delete_user.php', formData)
        .then(response => {
          console.log(response.data);
          that.manageuserinformation();  // 重新查询一次，用于刷新表格
          that.$message({
            showClose: true,
            message: '删除成功',
            type: 'success'
          });
        })
        .catch(error => {
          console.error('Error adding user:', error);
          that.$message({
            showClose: true,
            message: '删除失败，请重新检查',
            type: 'error'
          });
        });
    },
    // // 修改用户
    alterUser(userId) {
    //   // 实现删除具有指定userId的用户
    //   let that = this;
    //   let formData = new FormData();
    //   formData.append('userId', userId);
    //   // axios.delete(`/api/users/${userId}`)
    //   axios.post('http://localhost/snack_system/admin_delete_user.php', formData)
    //     .then(response => {
    //       console.log(response.data);
    //       that.manageuserinformation();  // 重新查询一次，用于刷新表格
    //       that.$message({
    //         showClose: true,
    //         message: '删除成功',
    //         type: 'success'
    //       });
    //     })
    //     .catch(error => {
    //       console.error('Error adding user:', error);
    //       that.$message({
    //         showClose: true,
    //         message: '删除失败，请重新检查',
    //         type: 'error'
    //       });
    //     });
    },
    fetchSnackInventory() {
      // axios.get('http://192.168.1.107/snack_system/fetch_snack_inventory.php')
      axios.get('http://localhost/snack_system/fetch_snack_inventory.php')
      .then(response => {
        this.snackInventory = response.data;
      })
      .catch(error => {
        console.error('Error fetching snack inventory:', error);
      });
    },
    fetchSnackRecord() {
      // axios.get('http://192.168.1.107/snack_system/purchase_historytotal.php')
      axios.get('http://localhost/snack_system/purchase_historytotal.php')
        .then(response => {
          this.snackrecord = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack records:', error);
        });
    },
    manageuserinformation() {
      // axios.get('http://192.168.1.107/snack_system/manage_user_information.php')
      axios.get('http://localhost/snack_system/manage_user_information.php')
      .then(response => {
        this.userrecord = response.data;
      })
      .catch(error => {
        console.error('Error fetching user information:', error);
      });
    },
  },
});