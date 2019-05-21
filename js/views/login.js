/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/* global swal */

let app = angular.module("loginApp", []);

app.controller('loginController', ($scope, $http) => {

    $scope.API = "http://localhost:3002/api/";
    $scope.MAIN = "http://localhost:3002/";
    
    $scope.login = function () {

        $http({
            method: 'POST',
            url: $scope.MAIN + 'login',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: 'usuario=' + $scope.usuario.usuario + '&password=' + $scope.usuario.password
        }).then((response, err) => {
            console.log(response.data);
            var data = response.data;

            if (data.msg === 'Encontrado') {
                $scope.saveUserLocalStorage(data.usuario);
                setTimeout(() => {
                    location.replace("index.html");
                }, 2000)
                
                swal({
                    title: "Error!",
                    text: "Bienvenido " + data.usuario.usuario,
                    icon: "success"
                }).then(() => {
                    location.replace("index.html");
                })
            } else {
                swal({
                    title: "Error!",
                    text: "Usuario o contraseña incorrecto",
                    icon: "error"
                })
            }
        });
    }

    $scope.saveUserLocalStorage = function (user) {
        window.localStorage.setItem('usuario', JSON.stringify(user));
    }

});