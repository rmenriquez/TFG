/**
 * Created by RaquelMarcos on 5/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Staff } from '../../models/staff';
import { StaffService } from '../../services/staff.service';

@Component({
  selector: 'app-staff-new',
  templateUrl: './staff-new.component.html',
  providers: [UserService, StaffService]
})
export class StaffNewComponent implements OnInit {

  private page_title: string;
  private identity;
  private staff: Staff;
  public status: string;

  private errors = {};

  constructor(
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _StaffService: StaffService
  ) {
    this.page_title = 'Create new staff';
    this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }
  }

  onSubmit(form){
    //console.log(this._userService.getIdentity());
    //console.log(this.food);
    //this.food.restaurant = this._userService.identity.id_user;
    console.log(form.value);
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

}
