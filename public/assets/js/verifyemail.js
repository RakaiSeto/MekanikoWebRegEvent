document.addEventListener("DOMContentLoaded", function () {
    var sendverifbutton = document.getElementById('sendverif');
    sendverifbutton.addEventListener("click", function(e) {
        e.preventDefault();
        var sendalert = document.getElementById('verify-alert');
        var sendinfo = document.getElementById('verify-info');
        this.disabled = true
        var self = this;

        var xhr = new XMLHttpRequest();
        
        xhr.open("POST", "/sendverify", false);
        
        var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfTokenMeta.getAttribute('content');

        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status == 200) {
                    sendinfo.classList.remove('d-none')
                    sendalert.classList.add('d-none')
                    sendinfo.innerHTML = "Success send verification code. Please check your email. You can resend the code after 30 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false
                    }, 30000);
                } else if (xhr.status == 503) {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Failed to send verification code. Server not available. Try again after 5 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false;
                    }, 5000);
                } else if (xhr.status == 500) {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Failed to send verification code. Unknown server error. Try again after 5 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false;
                    }, 5000);
                } else {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Session invalid. You will be logged out in 5 seconds."

                    setTimeout(() => {
                        location.replace('/signout')
                    }, 5000);
                }
            }
        };  
        xhr.send();
    })

    var button = document.getElementById('signup-submit');
    button.addEventListener('click', function(e) {
        e.preventDefault();
        var sendalert = document.getElementById('verify-error');
        var sendinfo = document.getElementById('verify-valid');
        this.disabled = true
        var self = this;

        var xhr = new XMLHttpRequest();
        
        xhr.open("POST", "/doverify", false);
        
        var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfTokenMeta.getAttribute('content');

        var otp = document.getElementById('verifx').value

        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

        var data = "otp=" + otp
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status == 200) {
                    sendinfo.classList.remove('d-none')
                    sendalert.classList.add('d-none')
                    sendinfo.innerHTML = "Success verify OTP, you will be redirected in 5 seconds."
                    setTimeout(() => {
                        location.replace('/dashboard')
                    }, 5000);
                } else if (xhr.status == 503) {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Failed to verify OTP. Server not available. Try again after 5 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false;
                    }, 5000);
                } else if (xhr.status == 500) {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Failed to verify OTP. Unknown server error. Try again after 5 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false;
                    }, 5000);
                } else if (xhr.status == 402) {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Failed to verify OTP. Wrong OTP. Try again after 5 seconds."
                    setTimeout(() => {
                        sendinfo.classList.add('d-none')
                        sendalert.classList.add('d-none')
                        self.disabled = false;
                    }, 5000);
                } else {
                    sendalert.classList.remove('d-none')
                    sendinfo.classList.add('d-none')
                    sendalert.innerHTML = "Session invalid. You will be logged out in 5 seconds."

                    setTimeout(() => {
                        location.replace('/signout')
                    }, 5000);
                }
            }
        };  
        xhr.send(data);
    });
});