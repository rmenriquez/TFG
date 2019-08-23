import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import { StaffService } from '../../services/staff.service';
import { UserService } from '../../services/user.service';
import {isUndefined} from "util";

@Component({
  selector: 'app-staff-all',
  templateUrl: './staff-all.component.html',
  providers: [StaffService]
})
export class StaffAllComponent implements OnInit {

  public staff: any[];

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _staffService: StaffService,
      private _userService: UserService,
      private  _translate: TranslateService

  ) {
      _translate.setDefaultLang('es');
  }

  ngOnInit() {
    this.getFoods();
    if (isUndefined(this._userService.getIdentity())) {
      this._router.navigate(["login"]);
    } else {
      this._staffService.getStaff().subscribe(
          response => {
            //console.log(response);
            this.staff = response;
          },
          error => {
            console.log(<any> error);
          }
      );
    }
  }

  getFoods(){
    this._staffService.getStaff().subscribe(
        response => {
          //console.log(response);
          this.staff = response;
        },
        error => {
          console.log(<any> error);
        }
    );
  }

  deleteStaff(id){
      this._staffService.deleteStaff(id).subscribe(
          response => {
              this.getFoods();
          },
          error => {

          }
      );
  }

}
