/**
 * Created by RaquelMarcos on 25/6/19.
 */

import {Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';
import {isUndefined} from "util";

@Component({
    selector: 'default',
    templateUrl: './default.component.html',
    providers: [UserService, FoodService]
})
export class DefaultComponent implements OnInit{
    public title: string;
    public foods: any = [];

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService
    ){
        this.title = 'Inicio';
    }

    ngOnInit(){
        console.log('default.component cargado correctamente!!');
        if(isUndefined(this._userService.getIdentity())){
            this._router.navigate(["login"]);
        }

    }


}