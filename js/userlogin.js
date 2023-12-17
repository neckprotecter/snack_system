
new Vue({
    el: '#app',
    data() {
        return {
            circleUrl: "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png",
            loginForm: {
                username: '',
                password: ''
            }
        };
    },
    methods: {
        login() {
            let that = this;
            let formData = new FormData();
            formData.append('username', that.loginForm.username);
            formData.append('password', that.loginForm.password);
            // axios.post('http://192.168.1.107/snack-management-system/userlogin_do.php', formData)
            axios.post('http://localhost/snack-management-system/userlogin_do.php', formData)
                .then(response => {
                    console.log('Login response:', response.data);
                    if (response.data.redirect) {
                        const redirectUrl = `${response.data.redirect}?username=${response.data.username}`;
                        console.log('欢迎你:', response.data.username);
                        window.location.href = redirectUrl;
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                });
        }
    }
});
