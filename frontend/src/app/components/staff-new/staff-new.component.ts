/**
 * Created by RaquelMarcos on 5/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import {TranslateService} from '@ngx-translate/core';

import { UserService } from '../../services/user.service';
import { Staff } from '../../models/staff';
import { StaffService } from '../../services/staff.service';

@Component({
  selector: 'app-staff-new',
  templateUrl: './staff-new.component.html',
  providers: [UserService, StaffService]
})
export class StaffNewComponent implements OnInit {

  private identity;
  private staff: Staff;
  public status: string;

  public errors = {};

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _StaffService: StaffService,
      private  _translate: TranslateService
  ) {
    this.identity = this._userService.getIdentity();
    _translate.setDefaultLang('es');
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }
  }

  onSubmit(form){
    this._StaffService.createStaff(form.value).subscribe(
        response=>{
          console.log(response);
          this.status = 'success';
          this._router.navigate(['/allStaff']);
        },
        error=>{
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
