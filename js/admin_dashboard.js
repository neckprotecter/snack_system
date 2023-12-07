new Vue({
  el: '#app',
  data() {
    return {
      username: '',
      currentTab: 'inventory',
      snackInventory: [],
      snackrecord:[],
      currentPage: 1,
      currentPage2: 1,
      pageSize: 6,
      pageSize2: 6,
      addStockForm: {
        snack_name: '',
        snack_quantity: 0,
        snack_price: 0,
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
    
      axios.post('http://192.168.1.107/snack_system/admin_add_snack.php', formData)
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
    fetchSnackInventory() {
      axios.get('http://192.168.1.107/snack_system/fetch_snack_inventory.php')
      .then(response => {
        this.snackInventory = response.data;
      })
      .catch(error => {
        console.error('Error fetching snack inventory:', error);
      });
    },
    fetchSnackRecord() {
      axios.get('http://192.168.1.107/snack_system/purchase_historytotal.php')
        .then(response => {
          this.snackrecord = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack records:', error);
        });
    },

  },
});
