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

    @ViewChild('registerForm',{ static: false }) registerForm: NgForm;

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
        this.user.name = this.registerForm.value.name;
        this.user.user = this.registerForm.value.user;
        this.user.password = this.registerForm.value.password;
        this.user.n_cli_wedding = this.registerForm.value.n_cli_wedding;
        this.user.n_cli_christening = this.registerForm.value.n_cli_christening;
        this.user.n_cli_communion = this.registerForm.value.n_cli_communion;
        this.user.n_cli_others = this.registerForm.value.n_cli_others;
        this.user.email = this.registerForm.value.email;

        //console.log(this._userService.pruebas());
        this._userService.register(this.user).subscribe(
            response => {
                console.log(response);
                if(response.status == 201){
                    this.status = 'success';
                    this.user = new User(1,'name','user',0,0,0,0,'email','password');
                    form.reset();
                }else{
                    this.status = 'error';
                }
            },
            error => {
                console.log(<any> error);
                console.log(this.status);
            }
        );

    }
}