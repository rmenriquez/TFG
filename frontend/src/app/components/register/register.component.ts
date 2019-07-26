/**
 * Created by RaquelMarcos on 17/6/19.
 */

import {Component, OnInit} from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
    selector: 'register',
    templateUrl: './register.component.html',
    providers: [UserService]
})
export class RegisterComponent implements OnInit{
    public user: User;
    public status: string;

    public errors = {};

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private  _translate: TranslateService

    ){

        _translate.setDefaultLang('es');
    }

    ngOnInit(){
        console.log('register.component cargado correctamente!!');
        if(!isUndefined(this._userService.getIdentity())){
            this._router.navigate(["/home"]);
        }
    }

    onSubmit(form){

        this._userService.register(form.value).subscribe(
            response => {
                console.log(response);
                if(response.status == 201){
                    this.status = 'success';
                    //this.user = new User(1,'name','user',0,0,0,0,'email','password');
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

    useLanguage(language: string) {
        this._translate.use(language);
    }
}