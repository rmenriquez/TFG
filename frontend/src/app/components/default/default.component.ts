/**
 * Created by RaquelMarcos on 25/6/19.
 */

import {Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';

@Component({
    selector: 'default',
    templateUrl: './default.component.html',
    providers: [UserService]
})
export class DefaultComponent implements OnInit{
    public title: string;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService
    ){
        this.title = 'Inicio';
    }

    ngOnInit(){
        console.log('default.component cargado correctamente!!');
    }


}