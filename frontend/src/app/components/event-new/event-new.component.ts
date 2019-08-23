/**
 * Created by RaquelMarcos on 05/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';
import {TranslateService} from '@ngx-translate/core';

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

  private identity;
  public event: Event;
  public status: string;

  public errors = {};

  foodForm: FormGroup;
  foods: Food[];
  foodsReady: boolean = false;
  foodsCreated;
  totalPriceFoodArray = [];
  totalPriceFoodReduce;

  numberStaff;
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
      private _staffService: StaffService,
      private  _translate: TranslateService
  ) {
    this.identity = this._userService.getIdentity();
      _translate.setDefaultLang('es');
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
      this.staff = staff;
      const controls = this.staff.map(c => new FormControl(false));

      this.staffForm = this._formBuilder.group({
        staff: new FormArray(controls)
      });
    });

  }

  onSubmit(form){
    this._eventService.createEvent(form.value).subscribe(
        response => {
          this.status = 'success';
          this.event = response;
          let id = response['id_event'];
          if(this.event.type === 'Boda' || this.event.type ==='boda'){
              if(Math.trunc(this.event.guests/this.identity.n_cli_wedding) == 0){
                  this.numberStaff = 1;
              }else{
                  this.numberStaff = Math.trunc(this.event.guests/this.identity.n_cli_wedding);
              }

          }
          if(this.event.type === 'Comunión' || this.event.type ==='Comunion' || this.event.type === 'comunión' || this.event.type === 'comunion'){
            if(Math.trunc(this.event.guests/this.identity.n_cli_communion) == 0){
                this.numberStaff = 1;
            }else{
                this.numberStaff = Math.trunc(this.event.guests/this.identity.n_cli_communion);
            }
          }
          if(this.event.type === 'Bautizo' || this.event.type ==='bautizo'){
              if(Math.trunc(this.event.guests/this.identity.n_cli_christening) == 0){
                  this.numberStaff = 1;
              }else{
                  this.numberStaff = Math.trunc(this.event.guests/this.identity.n_cli_christening);
              }
          }
          if(this.event.type === 'Otros' || this.event.type ==='otros'){
              if(Math.trunc(this.event.guests/this.identity.n_cli_others) == 0){
                  this.numberStaff = 1;
              }else{
                  this.numberStaff = Math.trunc(this.event.guests/this.identity.n_cli_others);
              }
          }
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

    selectedFoodIds.forEach((item, index) => {
      //Recorro los alergenos almacenados en BD
      //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
      let aux = this.foods.find(function (elem){
        return elem.id_food === item;
      });
      //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
      if(aux !== undefined){
        this.totalPriceFoodArray.push(parseFloat(aux.price));
      }
    });
    this.totalPriceFoodReduce = this.totalPriceFoodArray.reduce((a,b)=>a+b);
    this._eventService.setFoodsEvent(this.event.id_event, selectedFoodIds).subscribe(
        response => {

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

        },
        error => {
          console.log(<any> error);
          this.errors = error.error;
        }
    );
    let totalPriceEvent = ((this.totalPriceFoodReduce * this.event.guests) + (5*selectedStaffIds.length));
    this.event.price = totalPriceEvent;
    this._eventService.updateEvent(this.event.id_event, this.event).subscribe(
        response => {
          this._router.navigate(['eventDetail/',this.event.id_event]);
        },
        error => {
          console.log(<any> error);
          this.errors = error.error;
        }
    );
  }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}
