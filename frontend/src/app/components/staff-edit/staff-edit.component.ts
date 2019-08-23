/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import {Staff} from "../../models/staff";
import { StaffService } from '../../services/staff.service';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";


@Component({
    selector: 'app-staff-edit',
    templateUrl: './staff-edit.component.html',
    providers: [UserService, StaffService]
})

export class StaffEditComponent implements OnInit{

    public page_title: string;
    private status: string;
    private staff: Staff;

    private errors = {};

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _staffService: StaffService,
        private _userService: UserService,
        private  _translate: TranslateService
    ){
        _translate.setDefaultLang('es');
    }

    ngOnInit(){
        this._route.params.subscribe(params => {
            //console.log(params);
            let id_staff = params['id'];
            this.getStaff(id_staff);
        })
    }

    getStaff(id_staff){
        this._staffService.viewStaff(id_staff).subscribe(
            response => {
                //console.log(response);
                this.staff = response;
                this.page_title =  this.staff.name + ' ' + this.staff.surnames;
                if(isUndefined(this.staff)){
                    this._router.navigate(['/allStaff']);
                }
            },
            error => {
                console.log(<any> error);
                this._router.navigate(['/allStaff']);
            }
        );
    }

    onSubmit(form){
        console.log(this.staff.id_staff);
        this._staffService.updateStaff(this.staff, this.staff.id_staff).subscribe(
            response => {
                //console.log(response);
                if(response == null){
                    this.status = 'success';
                    this._router.navigate(['/allStaff']);
                }
                //console.log(status);
            },
            error => {
                console.log(<any> error);
                this.status = 'error';
                this.errors = error.error;
            }
        );
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}