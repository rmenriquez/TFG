/**
 * Created by RaquelMarcos on 19/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";
import { TranslateService } from '@ngx-translate/core';

@Component({
    selector: 'app-user-detail',
    templateUrl: './user-detail.component.html',
    providers: [UserService]
})
export class UserDetailComponent implements OnInit {

    public user;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private  _translate: TranslateService
    ) {
        _translate.setDefaultLang('es');

    }

    ngOnInit() {
        if(isUndefined(this._userService.getIdentity())){
            this._router.navigate(["login"]);
        }
        this.viewUser();
    }

    viewUser(){
        this._userService.viewUser().subscribe(
            response => {
                this.user = response;
                if(isUndefined(this.user)){
                    this._router.navigate(['home']);
                }

            },
            error => {
                this._router.navigate(['login']);
            }
        );

    }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}
