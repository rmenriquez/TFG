/**
 * Created by RaquelMarcos on 8/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { StaffService } from '../../services/staff.service';
import { Staff } from '../../models/staff';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
    selector: 'app-staff-detail',
    templateUrl: './staff-detail.component.html',
    providers: [UserService, StaffService]
})

export class StaffDetailComponent implements OnInit{
    private staff: Staff;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _staffService: StaffService,
        private _userService: UserService
    ){

    }

    ngOnInit(){
        this.getStaff();
    }

    getStaff(){
        this._route.params.subscribe(
            params =>{
                let id_staff = params['id'];
                this._staffService.viewStaff(id_staff).subscribe(
                    response => {
                        this.staff = response;
                        if(isUndefined(this.staff)){
                            this._router.navigate(['allStaff']);
                        }
                    },
                    error => {
                        console.log(<any> error);
                        this._router.navigate(['/allStaff']);
                    }
                );
            }
        );
    }
}