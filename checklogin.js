var username;
var password;


function validateLogin(username, password) {
  //send ajax request to server
  var req = new XMLHttpRequest();
  req.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  req.open("POST", "checklogin.php", true);
  //req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  req.send("username=&lname=Ford");
}
