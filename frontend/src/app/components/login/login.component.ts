/**
 * Created by RaquelMarcos on 17/6/19.
 */

import {Component, OnInit, EventEmitter, Output} from '@angular/core';
import {Router, ActivatedRoute, Params, Event} from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import  { User } from '../../models/user';
import { UserService } from '../../services/user.service';

@Component({
    selector: 'login',
    templateUrl: './login.component.html',
    providers: [UserService]
})
export class LoginComponent implements OnInit{
    public status: string;
    public token;
    public identity;

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
            console.log('login.component cargado correctamente!!');
            this.logout();

    }

    onSubmit(form){
        this._userService.signUp(form.value.user, form.value.password).subscribe(
            response => {
                this.status = 'success';

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

    logout(){
        this._route.params.subscribe(params => {
            let logout = +params['sure'];

            if(logout == 1){
                localStorage.removeItem('identity');
                localStorage.removeItem('token');
                localStorage.removeItem('identity del signUp')

                this.identity = null;
                this.token = null;

                //redirección
                this._router.navigate(['home']);
            }
        });
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }
}