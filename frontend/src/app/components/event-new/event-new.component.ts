/**
 * Created by RaquelMarcos on 05/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';


import { UserService } from '../../services/user.service';
import { Event } from '../../models/event';
import { EventService } from '../../services/event.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';
import { Staff } from '../../models/staff';
import { StaffService } from '../../services/staff.service';

@Component({
  selector: 'app-event-new',
  templateUrl: './event-new.component.html',
  providers: [UserService, EventService, FoodService, StaffService]
})
export class EventNewComponent implements OnInit {

  private page_title: string;
  private identity;
  private event: Event;
  private status: string;

  private errors = {};

  foodForm: FormGroup;
  foods: Food[];
  foodsReady: boolean = false;
  foodsCreated;

  staffForm: FormGroup;
  staff: Staff[];
  staffCreated;

  constructor(
      private _formBuilder: FormBuilder,
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _eventService: EventService,
      private _foodService: FoodService,
      private _staffService: StaffService
  ) {
    this.page_title = 'Create new event';
    this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
    if(this.identity == null){
      this._router.navigate(["/login"]);
    }
    this._foodService.getFoods().subscribe(foods => {
      this.foods = foods;
      const controls = this.foods.map(c => new FormControl(false));

      this.foodForm = this._formBuilder.group({
        foods: new FormArray(controls)
      });
    });
    this._staffService.getStaff().subscribe(staff => {
      console.log(staff);
      this.staff = staff;
      const controls = this.staff.map(c => new FormControl(false));

      this.staffForm = this._formBuilder.group({
        staff: new FormArray(controls)
      });
    });

  }

  onSubmit(form){
    console.log(form.value);
    this._eventService.createEvent(form.value).subscribe(
        response => {
          //console.log(response);
          this.status = 'success';
          this.event = response;
          //this._router.navigate(['/allEvents']);
          let id = response['id_event'];
          console.log(this.event);
        },
        error=> {
          console.log(<any> error);
          this.status = 'error';
          this.errors = error.error;
        }
    );
  }

  onSubmitFoods(form){
    const selectedFoodIds = this.foodForm.value.foods
        .map((v, i) => v ? this.foods[i].id_food : null)
        .filter(v => v !== null);

    console.log(selectedFoodIds);
    this._eventService.setFoodsEvent(this.event.id_event, selectedFoodIds).subscribe(
        response => {
          console.log(response);
          //this._router.navigate(['eventDetail/',this.event.id_event]);
          this.foodsReady = true;
          this.foodsCreated = true;
        },
        error => {
          console.log(<any> error);
          this.errors = error.error;
        }
    );
  }

  onSubmitStaff(form){
    const selectedStaffIds = this.staffForm.value.staff
        .map((v, i) => v ? this.staff[i].id_staff : null)
        .filter(v => v !== null);

    console.log(selectedStaffIds);
    this._eventService.setStaffEvent(this.event.id_event, selectedStaffIds).subscribe(
        response => {
          console.log(response);
          this._router.navigate(['eventDetail/',this.event.id_event]);
        },
        error => {
          console.log(<any> error);
          this.errors = error.error;
        }
    );
  }



}
