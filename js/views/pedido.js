var app = angular.module('pedidoApp', [])
    .controller('pedidoController', ($scope, $http) => {
        $scope.API = "http://localhost:3002/api/";
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
            var data = {};

            data[0] = { total: ($scope.pedido.length + 1) };
            data[1] = { usuario: $scope.usuario };

            $scope.pedido.forEach((menu, i) => {
                data[i + 2] = menu;
            })            

            var datos = $.param(data);

            $http({
                method: 'POST',
                url: $scope.API + 'pedido',
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
            })


            /*
            $http({
                method: 'POST',
                url: $scope.API + 'paypal/pay',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: datos
            }).then((response, err) => {
                var payment = response.data;
                console.log(payment);
                for (var i = 0; i < payment.links.length; i++) {
                    if (payment.links[i].rel === 'approval_url') {
                        // res.redirect(payment.links[i].href);
                        window.open(payment.links[i].href, '_blank')
                    }
                }
            })
            */

        }

        $scope.createOrden = function () {
            var fecha = moment(new Date()).format('YYYY-MM-DD');

            return new Promise((resolve, reject) => {
                $http({
                    method: 'POST',
                    url: $scope.API + 'pedido/index.php',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': "application/json, text/plain, */*" },
                    data: 'action=createOne' +
                        '&id_usuario=' + $scope.usuario.id_usuario +
                        '&total=' + $scope.total +
                        '&pagado=' + false +
                        '&fecha=' + fecha
                }).then((response, err) => {
                    console.log('pedido completado');
                    console.log(response.data);
                    swal({
                        title: 'Orden Completa',
                        text: 'Se ah completado su orden',
                        icon: 'success'
                    })
                    var data = '?action=getLastID';
                    $http({
                        method: 'GET',
                        url: $scope.API + 'pedido/index.php' + data
                    }).then((response, err) => {
                        console.log(response);
                        resolve(response.data.id_pedido);
                    })
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
