var app = angular.module('pedidoApp', [])
    .controller('pedidoController', ($scope, $http) => {

        $scope.pedido = [];
        $scope.total = 0;
        $scope.usuario = {};

        $scope.authLogin = function () {
            if (!localStorage.getItem("usuario")) {
                window.location.replace('login.html');
            } else {
                $scope.usuario = JSON.parse(window.localStorage.getItem('usuario'));
                console.log($scope.usuario);
            }
        }

        $scope.authLogin();


        $scope.reloadUser = function () {

            $http({
                method: 'POST',
                url: MAIN + 'login',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: 'usuario=' + $scope.usuario.usuario + '&password=' + $scope.usuario.password
            }).then((response, err) => {
                console.log(response.data);
                var data = response.data;
                $scope.saveUserLocalStorage(data.usuario);
            });
        }

        $scope.saveUserLocalStorage = function (user) {
            window.localStorage.setItem('usuario', JSON.stringify(user));
        }

        $scope.reloadUser();
        
        $scope.getPuntosTotales = function () {
            $http({
                method: 'GET',
                url: API + 'puntos'
            }).then((response, err) => {
                console.log(response);
                $scope.puntos = response.data;
            })
        }

        $scope.getPuntosTotales();

        $scope.getPedidoEnCurso = function () {
            $scope.total = 0;
            $scope.pedido = JSON.parse(localStorage.getItem('pedido')).items;
            console.log($scope.pedido);

            $scope.pedido.forEach((item) => {
                $scope.total += (item.cantidad * item.precio);
            })
        }

        $scope.getPedidoEnCurso();

        $scope.toInt = function (number) {
            return parseInt(number);
        }

        $scope.removeItemFromOrder = function (menu) {
            var temporal = [];
            $scope.pedido.forEach((item, i) => {
                if (String(item.id_menu) !== String(menu.id_menu)) {
                    temporal.push(item);
                }
            })
            console.log(temporal);

            $scope.pedido = temporal;

            localStorage.setItem("pedido", JSON.stringify({ items: $scope.pedido }));

            $scope.getPedidoEnCurso();

            swal({
                title: 'Menu eliminado',
                text: 'Se ah eliminado el men tu carrito',
                icon: 'success'
            })
        }

        $scope.confirmarOrden = function () {
            console.log($scope.pedido);
            if (Number($scope.usuario.puntos) < Number($scope.total)) {
                swal({
                    title: 'Puntos insuficientes',
                    text: 'No se cuentan con los puntos suficientes',
                    icon: 'error'
                })
            } else {


                var data = {};

                data[0] = { total: ($scope.pedido.length + 1) };
                data[1] = { usuario: $scope.usuario };

                $scope.pedido.forEach((menu, i) => {
                    data[i + 2] = menu;
                })

                var datos = $.param(data);

                $scope.removePuntosUsuario()
                    .then(() => {
                        $http({
                            method: 'POST',
                            url: API + 'pedido',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            data: datos
                        }).then((response, err) => {
                            console.log('pedido completado');
                            console.log(response.data);
                            swal({
                                title: 'Orden Completa',
                                text: 'Se ah completado su orden',
                                icon: 'success'
                            })

                            localStorage.setItem("pedido", JSON.stringify({ items: [] }));
                            localStorage.removeItem("pedido");

                            $scope.pedido = [];
                            $scope.total = 0;

                            $scope.rel
                        })
                    })

            }
        }

        $scope.removePuntosUsuario = function () {
            return new Promise((resolve, reject) => {
                $http({
                    method: 'POST',
                    url: API + 'usuario/removePuntos/' + $scope.usuario._id,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: 'totalPuntos=' + (Number($scope.usuario.puntos) - Number($scope.total))
                })
                    .then((response, err) => {
                        console.log(response.data);
                        swal({
                            title: "Puntos Agregados",
                            text: "Se han agregado los puntos",
                            icon: "success"
                        })
                        resolve(true);
                    })
            })

        }

        $scope.vaciarCarrito = function () {
            localStorage.setItem("pedido", JSON.stringify({ items: [] }));
            localStorage.removeItem("pedido");

            $scope.pedido = [];
            $scope.total = 0;

            swal({
                title: 'Carrito Vaciado',
                text: 'Se ah vaciado tu carrito',
                icon: 'success'
            })
        }
    })
