/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';
import {TranslateService} from '@ngx-translate/core';

import { EventService } from '../../services/event.service';
import { Event } from '../../models/event';

import { Staff } from '../../models/staff';
import { StaffService } from '../../services/staff.service';

import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';

import { UserService } from '../../services/user.service';
import { LoginComponent } from '../login/login.component';
import {isUndefined} from "util";

@Component({
    selector: 'app-event-edit',
    templateUrl: './event-edit.component.html',
    providers: [UserService, EventService, FoodService, StaffService]
})
export class EventEditComponent implements OnInit {

    private identity;
    public page_title: string;
    private status: string;

    public event: Event;

    private errors: {};

    foodForm: FormGroup;
    foods: Food[];
    foodsEvent: any[];
    foodsReady: boolean = false;
    foodsEdited;

    staffForm: FormGroup;
    staff: Staff[];
    staffEvent: any[];
    staffEdited;

    constructor(
        private _formBuilder: FormBuilder,
        private _route: ActivatedRoute,
        private _router: Router,
        private _eventService: EventService,
        private _userService: UserService,
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
        this._route.params.subscribe(params => {
            let id_event = params['id'];
            this.getEvent(id_event);
        });
    }

    getEvent(id_event){
        this._eventService.viewEvent(id_event).subscribe(
            response => {
                this.event = response;
                this.page_title = this.event.type + '  ' + this.event.name + ' - ' + this.event.date;
                if(isUndefined(this.event)){
                    this._router.navigate(['allEvents']);
                }
                this.foodsEvent = this.event.food;
                this.staffEvent = this.event.staff;

                this.getFoods();
                this.getStaff();
            },
            error => {
                console.log(<any> error);
                this.status = 'error';
                this.errors = error.error;
                this._router.navigate(['allEvents']);
            }
        );
    }

    onSubmit(form){
        this._eventService.updateEvent(this.event, this.event.id_event).subscribe(
            response => {
                if(response == null){
                    this.status = 'success';
                    this._router.navigate(['/allEvents']);
                }

            },
            error => {
                console.log(<any> error);
                this.status = 'error';
                this.errors = error.error;
            }
        );

    }

    onSubmitFoods(form){
        //Recojo las comidas seleccionados

        const selectedFoodIds = this.foodForm.value.foods
            .map((v, i) => v ? this.foods[i].id_food : null)
            .filter(v => v !== null);

        let toDelete = [];
        let toAdd = [];
        selectedFoodIds.forEach((item, index) => {
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.foodsEvent.find(function (elem){
                return elem['id_food'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){
                toAdd.push(item);
            }
        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let foodBd of this.foodsEvent){
            let aux = selectedFoodIds.find(function (elem){
                return elem === foodBd['id_food'];
            });
            if(aux === undefined){

                toDelete.push(foodBd['id_food']);
            }
        }


        let send = {toAdd, toDelete};

        this._eventService.updateEventFood(this.event.id_event, send).subscribe(
            response => {
                this.foodsEdited = 'success';
            },
            error => {
                this.foodsEdited = 'error';
                console.log(<any> error);
                this.errors = error.error;
            }
        );
    }

    getFoods(){
        this._foodService.getFoods().subscribe((foods: Food[]) => {
            this.foods = foods;
            let controls = [];
            for(let food of this.foods) {

                if ((this.foodsEvent.find(aux => aux['id_food'] === food['id_food'])) != undefined) {
                    controls.push(new FormControl(true));
                } else {
                    controls.push(new FormControl(false));
                }
            }
            this.foodForm = this._formBuilder.group({
                foods: new FormArray(controls)
            });

        });

    }

    onSubmitStaff(form){
        //Recojo las comidas seleccionados
        const selectedStaffIds = this.staffForm.value.staff
            .map((v, i) => v ? this.staff[i].id_staff : null)
            .filter(v => v !== null);

        console.log(selectedStaffIds);

        let toDelete = [];
        let toAdd = [];
        selectedStaffIds.forEach((item, index) => {
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.staffEvent.find(function (elem){
                return elem['id_staff'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){

                toAdd.push(item);
            }
        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let staffBd of this.staffEvent){
            let aux = selectedStaffIds.find(function (elem){
                return elem === staffBd['id_staff'];
            });
            if(aux === undefined){

                toDelete.push(staffBd['id_staff']);
            }
        }

        let send = {toAdd, toDelete};

        this._eventService.updateEventStaff(this.event.id_event, send).subscribe(
            response => {
                this.staffEdited = 'success';
            },
            error => {
                this.staffEdited = 'error';
                console.log(<any> error);
                this.errors = error.error;
            }
        );
    }

    getStaff(){
        console.log(this.event.id_event);
        this._staffService.getStaff().subscribe((staff: Staff[]) => {
            this.staff = staff;
            let controls = [];
            for(let person of this.staff) {

                if ((this.staffEvent.find(aux => aux['id_staff'] === person['id_staff'])) != undefined) {
                    controls.push(new FormControl(true));
                } else {
                    controls.push(new FormControl(false));
                }
            }
            this.staffForm = this._formBuilder.group({
                staff: new FormArray(controls)
            });

        });
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}
