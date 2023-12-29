new Vue({
  el: '#app',
  data() {
    return {
      username: '',
      currentTab: 'inventory',
      snackInventory: [],
      bill: {},
      snackrecord:[],
      userrecord:[],
      currentPage: 1,
      currentPage2: 1,
      pageSize: 6,
      pageSize2: 6,
      inputStr: '',
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
      dialogVisible: false,
      editForm: {
        snack_id: 0,
        date: '',
        snack_name: '',
        snack_quantity: 0,
        add_quantity: 0,
        snack_price: 0,
        snack_sold: 0,
        unit_price: 0,
      },
      billresultDialogVisible: false,
      dateDialogVisible: false,
      dateRange: [], // 查询账单选择的时间范围
      pickerOptions: {  // 时间选择
        shortcuts: [
          {
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);

              console.log('Start Date:', start.toLocaleString());
              console.log('End Date:', end.toLocaleString());
            }
          },
          {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);

              console.log('Start Date:', start.toLocaleString());
              console.log('End Date:', end.toLocaleString());
            }
          },
          {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);

              console.log('Start Date:', start.toLocaleString());
              console.log('End Date:', end.toLocaleString());
            }
          }
        ]
      }
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
    // 退出登录
    logout() {
      window.location.href = 'logout.php';
    },
    // excel导出
    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.snackInventory);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'SnackData');
      const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
      const saveAs = window.saveAs || FileSaver.saveAs || window.FileSaver.saveAs;
      if (saveAs) {
        saveAs(new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }), 'snack_inventory.xlsx');
      } else {
        console.error('Error: saveAs function not found. Make sure FileSaver is properly imported.');
      }
    },
    // 展示库存
    showInventory() {
      this.currentPage = 1;
      this.currentPage2 = 1;
      this.pageSize = 6;
      this.pageSize2 = 6;
      this.currentTab = 'inventory';
      this.fetchSnackInventory(); 
    },
    // 添加库存tab页面
    showAddStock() {
      this.currentTab = 'addStock';
    },
    // 模糊搜索
    queryInventory() {
      let that = this;
      // axios.get('http://192.168.1.107/snack-management-system/query_snack.php', {
      axios.get('http://localhost/snack-management-system/query_snack.php', {
        params: {
          snackname: that.inputStr
        }
      })
        .then(response => {
          this.snackInventory = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack inventory:', error);
        });
    },
    // 展示购买记录
    showCheckrecord() {
      this.currentPage = 1;
      this.currentPage2 = 1;
      this.pageSize = 6;
      this.pageSize2 = 6;
      this.currentTab = 'checkrecord';
      this.fetchSnackRecord(); 
    },
    // 修改零食信息
    changeItem(row) {
      this.editForm = { ...row };
      this.dialogVisible = true;
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
    // 权限显示
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
    // 成员类型显示
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
    // 关闭成员信息管理卡片
    closemanageuserCard(){
      this.dialogVisible = false;
      this.isView = false;
      this.isEdit = false;
    },
    // 展示成员信息管理卡片
    showmodifyUserCard(userid, username, password, role, member){
      this.dialogVisible = true;
      let that = this;
      that.modifyUserForm.userid = userid;
      that.modifyUserForm.m_username = username;
      that.modifyUserForm.m_password = password;
      that.modifyUserForm.m_role = role;
      that.modifyUserForm.m_member = member;
    },
    // 分页显示
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
    // 添加库存
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
    // 展示日期对话框
    showDateDialog() {
      this.dateDialogVisible = true;
    },
    // 日期选择处理
    handleDateSelection() {
      let that = this;
      // 处理选择的开始和结束时间
      const startDate = this.dateRange[0];
      const endDate = this.dateRange[1];
      const requestData = {
        startDate: startDate.toLocaleString(),
        endDate: endDate.toLocaleString()
      };
      
      axios.post('http://localhost/snack-management-system/admin_fetch_bill.php', requestData)
      .then(response => {
        // console.log(response.data);
        this.bill = response.data;
        console.log(this.bill);
        that.$message({
          showClose: true,
          message: '查询成功',
          type: 'success'
        });
      })
      .catch(error => {
          console.error('Error adding user:', error);
          that.$message({
          showClose: true,
          message: '查询失败:',
          type: 'error'
        });
      });

      this.dateDialogVisible = false;  // 关闭日期显示对话框
      this.billresultDialogVisible = true;  // 打开结果展示对话框
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
    // 提交用户信息修改
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
    // 关闭对话框确认
    handleClose(done) {
      this.$confirm('确认关闭？')
        .then(_ => {
          done();
        })
        .catch(_ => {});
    },
    // 查询库存
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
    // 查询购买记录
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
    // 查询用户信息
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

    resetForm() {
      this.editForm = {
        snack_id: 0,
        date: '',
        snack_name: '',
        add_quantity: 0,
        snack_price: 0,
        snack_sold: 0,
        unit_price: 0,
      };
    },
    updateItem() {
      console.log('editForm updated', this.editForm);

      this.dialogVisible = false;
    },
  },
});