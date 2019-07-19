var app = angular.module('historialApp', [])
    .controller('historialController', ($scope, $http) => {

        $scope.usuario = {};
        $scope.pedidos = {};
        
        $scope.authLogin = function () {
            if (!localStorage.getItem("usuario")) {
                window.location.replace('login.html');
            } else {
                $scope.usuario = JSON.parse(window.localStorage.getItem('usuario'));
                console.log($scope.usuario);
            }
        }

        $scope.authLogin();

        $scope.getHistorial = function () {
           var data = 'id=' + $scope.usuario._id;
            $http({
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                url: API + 'pedido/getHistorialUsuario',
                data: data
            })
            .then((response, err)=>{
                $scope.pedidos = response.data;
                console.log($scope.pedidos);

                response.data.forEach((pedido, i) => {
                    $scope.pedidos[i].fecha = moment(pedido.fecha, 'YYYY-MM-DD').format('YYYY-MM-DD');

                    pedido.detalles.forEach((detalles, x) => {
                        $scope.pedidos[i].detalles[x].menu.dia = $scope.detDaySpanish(moment(detalles.menu.fecha).format('dddd'));
                    })
                })
            })
        }

        $scope.detDaySpanish = function (day) {
            switch (day) {
                case 'Monday':
                    return 'Lunes';
                    break;
                case 'Tuesday':
                    return 'Martes';
                    break;
                case 'Wednesday':
                    return 'Miercoles';
                    break;
                case 'Thursday':
                    return 'Jueves';
                    break;
                case 'Friday':
                    return 'Viernes';
                    break;
                case 'Saturday':
                    return 'Sabado';
                    break;
                case 'Sunday':
                    return 'Domingo';
                    break;
            }
        }

        $scope.pedido = {};
        $scope.selectPedido = function (pedido) {
            $scope.pedido = pedido;
        }
   
        $scope.getHistorial();
    })