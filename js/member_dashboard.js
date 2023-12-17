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
      if (username) {
          this.username = username;
      }
      console.log('username: ' + this.username);
      this.fetchSnackInventory();
    },
    methods: {
      logout() {
        window.location.href = 'logout.php';
      },
      handleInput(row) {
        if (row.purchaseQuantity > row.remaining_quantity) {
          row.purchaseQuantity = row.remaining_quantity;
        } else if (row.purchaseQuantity < 0) {
          row.purchaseQuantity = 0;
        }
      },
      showInventory() {
        this.currentPage = 1;
        this.currentPage2 = 1;
        this.pageSize = 6;
        this.pageSize2 = 6;
        this.currentTab = 'inventory';
        this.fetchSnackInventory(); 
      },
      showBuyhistory() {
        this.currentPage = 1;
        this.currentPage2 = 1;
        this.pageSize = 6;
        this.pageSize2 = 6;
        this.currentTab = 'buyhistory';
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
      fetchSnackInventory() {
        // axios.get('http://192.168.1.107/snack-management-system/fetch_snack_inventory_forUser.php')
        axios.get('http://localhost/snack-management-system/fetch_snack_inventory_forUser.php')
        .then(response => {
          console.log("res",response.data);
          this.snackInventory = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack inventory:', error);
        });
      },
      fetchSnackRecord() {
        let that = this;
        // axios.get('http://192.168.1.107/snack-management-system/purchase_history.php', {
        axios.get('http://localhost/snack-management-system/purchase_history.php', {
          params: {
            username: that.username
          }
        })
        .then(response => {
          that.snackrecord = response.data;
        })
        .catch(error => {
          console.error('Error fetching snack records:', error);
        });
      },
      purchaseItem(row) {
        let that = this;
        const purchaseData = {
          username: this.username,
          snackname: row.snack_name,
          purchaseQuantity: row.purchaseQuantity,
          totalprice: row.purchaseQuantity* row.unit_price,
        };
        console.log('purchaseinfo',purchaseData);
        // axios.post('http://192.168.1.107/snack-management-system/purchase.php', purchaseData)
        axios.post('http://localhost/snack-management-system/purchase.php', purchaseData)
          .then(response => {
            console.log('购买成功:', response.data);
            that.$message({
              showClose: true,
              message: '购买成功',
              type: 'success'
            })
          })
          .catch(error => {
            console.error('购买失败:', error);
            that.$message({
              showClose: true,
              message: '购买失败',
              type: 'error'
            });
          });
      },
    },
  });
  