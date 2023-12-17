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
      dialogVisible: false,
      dialogTitle: "",
      isView: false,
      isEdit: false,
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
      modifyUserForm: {
        userid: 0,
        m_username: '',
        m_password: '',
        m_role: 1,
        m_member: 0,
      },
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
      this.fetchuserinformation(); 
    },
    roleFormat(row, column) {
      if (row.role === 'admin') {
        return '管理员'
      } 
      else if(row.role === 'member') {
        return '普通成员'
      }
      else{
        return '数据错误'
      }
    },
    memberFormat(row, column) {
      if (row.member === '0') {
        return '实习成员'
      } 
      else if(row.member === '1')  {
        return '正式成员'
      }
      else{
        return '数据错误'
      }
    },
    closemanageuserCard(){
      this.dialogVisible = false;
      this.isView = false;
      this.isEdit = false;
    },
    showmodifyUserCard(userid, username, password, role, member){
      this.dialogVisible = true;
      let that = this;
      that.modifyUserForm.userid = userid;
      that.modifyUserForm.m_username = username;
      that.modifyUserForm.m_password = password;
      that.modifyUserForm.m_role = role;
      that.modifyUserForm.m_member = member;
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
    
      // axios.post('http://192.168.1.107/snack-management-system/admin_add_snack.php', formData)
      axios.post('http://localhost/snack-management-system/admin_add_snack.php', formData)
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

      // axios.post('http://192.168.1.107/snack-management-system/admin_add_user.php', formData)
      axios.post('http://localhost/snack-management-system/admin_add_user.php', formData)
        .then(response => {
          console.log(response.data);
          that.fetchuserinformation();  // 重新查询一次，用于刷新表格
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
    deleteUser(userId, username, userrole, usermember) {
      this.$confirm('是否确认删除  用户ID为: '+ userId + '\t姓名: ' + username + '\t权限: ' + userrole + '\t身份: ' + usermember + '\t的用户?', {
        confirmButtonText: '确定删除',
        cancelButtonText: '取消',
        type: 'warning'
      }).then( () => {
        // 实现删除具有指定userId的用户
        let that = this;
        let formData = new FormData();
        formData.append('userId', userId);
        // axios.post('http://192.168.1.107/snack-management-system/admin_delete_user.php', formData)
        axios.post('http://localhost/snack-management-system/admin_delete_user.php', formData)
          .then(response => {
            console.log(response.data);
            that.fetchuserinformation();  // 重新查询一次，用于刷新表格
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
          })
      });
    },
    submitmodifyUserForm(modify_userid){
      this.closemanageuserCard();
      this.modifyUserinformation(modify_userid); 
      this.dialogVisible = false;
    },
    // 修改用户信息
    modifyUserinformation(modify_userid){
      this.dialogVisible = true;
      let that = this;
      let formData = new FormData();
      formData.append('userId', modify_userid);
      formData.append('modify_username', that.modifyUserForm.m_username);
      formData.append('modify_password', that.modifyUserForm.m_password);
      formData.append('modify_role', that.modifyUserForm.m_role);
      formData.append('modify_member', that.modifyUserForm.m_member);

      // axios.post('http://192.168.1.107/snack-management-system/admin_modify_user.php', formData)
      axios.post('http://localhost/snack-management-system/admin_modify_user.php', formData)
      .then(response => {
        console.log(response.data);
        that.fetchuserinformation();  // 重新查询一次，用于刷新表格
        that.$message({
          showClose: true,
          message: '修改成功',
          type: 'success'
        });
      })
      .catch(error => {
        console.error('Error modify user:', error);
        that.$message({
          showClose: true,
          message: '修改失败，请检查输入',
          type: 'error'
        });
      });
    },
    handleClose(done) {
      this.$confirm('确认关闭？')
        .then(_ => {
          done();
        })
        .catch(_ => {});
    },
    fetchSnackInventory() {
      // axios.get('http://192.168.1.107/snack-management-system/fetch_snack_inventory.php')
      axios.get('http://localhost/snack-management-system/fetch_snack_inventory.php')
      .then(response => {
        this.snackInventory = response.data;
      })
      .catch(error => {
        console.error('Error fetching snack inventory:', error);
      });
    },
    fetchSnackRecord() {
      // axios.get('http://192.168.1.107/snack-management-system/purchase_historytotal.php')
      axios.get('http://localhost/snack-management-system/purchase_historytotal.php')
        .then(response => {
          this.snackrecord = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack records:', error);
        });
    },
    fetchuserinformation() {
      // axios.get('http://192.168.1.107/snack-management-system/getch_user_information.php')
      axios.get('http://localhost/snack-management-system/fetch_user_information.php')
      .then(response => {
        this.userrecord = response.data;
      })
      .catch(error => {
        console.error('Error fetching user information:', error);
      });
    },
  },
});