/**
 * Created by RaquelMarcos on 17/6/19.
 */

import { Component, OnInit } from '@angular/core';

import { Router, ActivatedRoute, Params } from '@angular/router';
import  { User } from '../../models/user';
import { UserService } from '../../services/user.service';

@Component({
    selector: 'login',
    templateUrl: './login.component.html',
    providers: [UserService]
})
export class LoginComponent implements OnInit{
    public title: string;
    public user: User;
    public status: string;
    public token;
    public identity;

    public errors = {};

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService
    ){
        this.title = 'Identificate';
        this.user =  new User(1,'name','user',0,0,0,0,'email','password');
    }

    ngOnInit(){
            console.log('login.component cargado correctamente!!');
            this.logout();

            //console.log(userFromService);
    }

    onSubmit(form){
        this.user.user = form.value.user;
        this.user.password = form.value.password;
        //console.log(this.user);
        this._userService.signUp(this.user).subscribe(
            response => {
                this.status = 'success';
                //conseguir aqui el token
                //console.log(response);
                this.user.name = response.name;
                this.user.n_cli_wedding = response.n_cli_wedding;
                this.user.n_cli_christening = response.n_cli_christening;
                this.user.n_cli_communion = response.n_cli_communion;
                this.user.n_cli_others = response.n_cli_others;
                this.user.email = response.email;

                this.user.authdata = btoa(this.user.user + ':'+this.user.password);
                this.token = 'Basic '+ btoa(this.user.user + ':'+this.user.password);
                localStorage.setItem('token', this.token);
                this.identity = this.user;
                localStorage.setItem('identity', JSON.stringify(this.identity));
                console.log(this.user);
                //Redireccion
                this._router.navigate(['home']);
            },
            error => {
                this.status = 'error';
                console.log(<any> error);
                this.errors = error.error;
            }
        );
    }

    /*onSubmit(form){
        this.user.user = form.value.user;
        this.user.password = form.value.password;
        this._userService.login(this.user).pipe(first()).subscribe(
            data => {
                this.status = 'success';
                this._router.navigate(['home']);
            },
            error =>{
                this.status = 'error';
                console.log(<any> error);
                this.errors = error.error;
            }
        )

    }*/

    logout(){
        this._route.params.subscribe(params => {
            let logout = +params['sure'];

            if(logout == 1){
                localStorage.removeItem('identity');
                localStorage.removeItem('token');

                this.identity = null;
                this.token = null;

                //redirección
                this._router.navigate(['home']);
            }
        });
    }
}