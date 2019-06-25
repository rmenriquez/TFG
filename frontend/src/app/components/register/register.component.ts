/**
 * Created by RaquelMarcos on 17/6/19.
 */

import {Component, OnInit, ViewChild} from '@angular/core';
import { NgForm } from '@angular/forms';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';

@Component({
    selector: 'register',
    templateUrl: './register.component.html',
    providers: [UserService]
})
export class RegisterComponent implements OnInit{
    public title: string;
    public user: User;
    public status: string;

    public errors = {};

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService
    ){
        this.title = 'Regístrate';
        this.user = new User(1,'name','user',0,0,0,0,'email','password');
    }

    ngOnInit(){
        console.log('register.component cargado correctamente!!');
    }

    onSubmit(form){
        this.user.name = form.value.name;
        this.user.user = form.value.user;
        this.user.password = form.value.password;
        this.user.n_cli_wedding = form.value.n_cli_wedding;
        this.user.n_cli_christening = form.value.n_cli_christening;
        this.user.n_cli_communion = form.value.n_cli_communion;
        this.user.n_cli_others = form.value.n_cli_others;
        this.user.email = form.value.email;

        //console.log(this._userService.pruebas());
        this._userService.register(this.user).subscribe(
            response => {
                console.log(response);
                if(response.status == 201){
                    this.status = 'success';
                    this.user = new User(1,'name','user',0,0,0,0,'email','password');
                    form.reset();
                }
            },
            error => {
                console.log(error.error);
                this.status = 'error';
                this.errors = error.error;
                console.log(<any> error);
                console.log(this.status);
            }
        );

    }
}