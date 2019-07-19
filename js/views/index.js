var app = angular.module('indexApp', [])
    .controller('indexController', ($scope, $http) => {

        $scope.menus = [];
        $scope.startDate = null;
        $scope.endDate = null;
        $scope.dayStartDate = null;
        $scope.dayEndDate = null;

        $scope.cargandoMenus = true;

        $scope.getFechaSemana = function () {

            $scope.add = 0;
            $scope.remove = 0;

            var date = new Date();

            var dayOfWeek = moment(date, 'YYYY-MM-DD').format('dddd');
            console.log(dayOfWeek);

            switch (dayOfWeek) {
                case 'Monday':
                    $scope.add = 4;
                    $scope.remove = 0;
                    break;
                case 'Tuesday':
                    $scope.add = 3;
                    $scope.remove = 1;
                    break;
                case 'Wednesday':
                    $scope.add = 2;
                    $scope.remove = 2;
                    break;
                case 'Thursday':
                    $scope.add = 1;
                    $scope.remove = 3;
                    break;
                case 'Friday':
                    $scope.add = 0;
                    $scope.remove = 4;
                    break;
                case 'Saturday':
                    break;
                case 'Sunday':
                    break;
            }

            $scope.startDate = moment(date).subtract($scope.remove, "days").format('YYYY-MM-DD');
            $scope.endDate = moment(date).add($scope.add, "days").format('YYYY-MM-DD');
            console.log($scope.startDate, $scope.endDate);
        }

        $scope.menusAgrupados = {};
        $scope.getMenuSemana = function () {
            $scope.menusAgrupados = {};
            var data = 'fecha_inicio=' + $scope.startDate + '&fecha_fin=' + $scope.endDate;
            $http({
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                url: API + 'menu/getByDate',
                data: data
            }).then((response, err) => {
                console.log("obteniendo menus")
                console.log(response.data);
                var data = response.data;

                console.log(data.menus.length);

                $scope.menus = data.menus;

                $scope.menus.forEach((menu, i) => {
                    var date = new Date(menu.fecha);
                    $scope.menus[i].dia = $scope.detDaySpanish(moment(date, 'YYYY-MM-DD').format('dddd'));
                    $scope.menus[i].fecha = moment(date, 'YYYY-MM-DD').format('YYYY-MM-DD');
                })

                var y = 1;
                var x = 0;
                for (var i = 0; i < ((data.menus.length) / 2); i++) {

                    $scope.menusAgrupados[i] = {
                        menu1: $scope.menus[x],
                        menu2: $scope.menus[y]
                    };
                    y += 2;
                    x += 2;
                }
                console.log("obteniendo menus agrupados")
                console.log($scope.menusAgrupados);
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

        $scope.getFechaSemana();
        $scope.getMenuSemana();

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

        $scope.Menu = {};
        $scope.addToCart = function (Menu) {
            console.log(Menu);
            $scope.Menu = Menu
            console.log($scope.usuario.alumno.length);

            if ($scope.usuario.alumno.length > 1) {

                $('#exampleModal').modal('show');

            } else {

                var addItem = Menu;
                var existe = false;
                var posicion = 0;

                if (!localStorage.getItem("pedido")) {
                    localStorage.setItem('pedido', JSON.stringify({ items: [] }));
                }

                var pedidoActual = JSON.parse(localStorage.getItem("pedido"));

                if (pedidoActual.items)
                    pedidoActual.items.forEach((item, i) => {
                        if (addItem._id == item._id) {
                            existe = true;
                            posicion = 1;
                        }
                    })

                if (!existe) {
                    addItem.cantidad = 1;
                    addItem.id_alumno = $scope.usuario.alumno[0]._id;
                    addItem.alumno = $scope.usuario.alumno[0].nombre + " " + $scope.usuario.alumno[0].apellido_paterno + " " + $scope.usuario.alumno[0].apellido_materno
                    pedidoActual.items.push(addItem);
                } else {
                    pedidoActual.items[posicion].cantidad++;
                }
                localStorage.setItem("pedido", JSON.stringify(pedidoActual));
                console.log(JSON.parse(localStorage.getItem('pedido')));
                swal({
                    title: "Agregado a tu carrito!",
                    text: "Se ah agregado a tu carrito!",
                    icon: "success",
                });
            }

        }

        $scope.addToCartWithAlumn = function () {
            console.log($scope.alumnoSeleccionado);
            var alumno = JSON.parse($scope.alumnoSeleccionado);

            var addItem = $scope.Menu;
            var existe = false;
            var posicion = 0;

            if (!localStorage.getItem("pedido")) {
                localStorage.setItem('pedido', JSON.stringify({ items: [] }));
            }

            var pedidoActual = JSON.parse(localStorage.getItem("pedido"));

            pedidoActual.items.forEach((item, i) => {
                if (addItem._id == item._id) {
                    existe = true;
                    posicion = 1;
                }
            })

            if (!existe) {
                addItem.cantidad = 1;
                addItem.id_alumno = alumno._id;
                addItem.alumno = alumno.nombre + " " + alumno.apellido_paterno + " " + alumno.apellido_materno
                pedidoActual.items.push(addItem);
            } else {
                pedidoActual.items[posicion].cantidad++;
            }
            localStorage.setItem("pedido", JSON.stringify(pedidoActual));
            console.log(JSON.parse(localStorage.getItem('pedido')));

            swal({
                title: "Agregado a tu carrito!",
                text: "Se ah agregado a tu carrito!",
                icon: "success",
            });

        }
    })