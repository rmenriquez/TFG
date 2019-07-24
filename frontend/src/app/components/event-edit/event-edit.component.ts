/**
 * Created by RaquelMarcos on 9/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';

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
    private page_title: string;
    private status: string;

    private event: Event;

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
        private _staffService: StaffService
//        private _loginComponent: LoginComponent
    ) {
        this.identity = this._userService.getIdentity();
    }

    ngOnInit() {
        if(this.identity == null){
            //this._loginComponent.logout();
            this._router.navigate(["/login"]);

        }
        this._route.params.subscribe(params => {
            console.log(params);
            let id_event = params['id'];
            this.getEvent(id_event);
        });
    }

    getEvent(id_event){
        this._eventService.viewEvent(id_event).subscribe(
            response => {
                console.log(response);

                //console.log('estoy dentro');
                this.event = response;
                this.page_title = 'Editar ' + this.event.type + ' de ' + this.event.name + ' - ' + this.event.date;
                if(isUndefined(this.event)){
                    this._router.navigate(['allEvents']);
                }
                this.foodsEvent = this.event.food;
                this.staffEvent = this.event.staff;
                console.log(this.foodsEvent);
                console.log(this.staffEvent);
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
        //Servicio
        console.log(this.event.id_event);
        this._eventService.updateEvent(this.event, this.event.id_event).subscribe(
            response => {
                if(response == null){
                    this.status = 'success';
                    this._router.navigate(['/allEvents']);
                }
                console.log(this.status);

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
        console.log(this.foodsEvent);
        console.log(this.foodForm.value.foods);
        const selectedFoodIds = this.foodForm.value.foods
            .map((v, i) => v ? this.foods[i].id_food : null)
            .filter(v => v !== null);

        console.log(selectedFoodIds);

        let toDelete = [];
        let toAdd = [];
        selectedFoodIds.forEach((item, index) => {
            //console.log(item);
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.foodsEvent.find(function (elem){
                return elem['id_food'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){
                //console.log('no existe en foodallergens');
                //selectedAndUnselected.push(item);
                //enableds.push(1);
                toAdd.push(item);
            }
        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let foodBd of this.foodsEvent){
            let aux = selectedFoodIds.find(function (elem){
                console.log(foodBd['id_food']);
                return elem === foodBd['id_food'];
            });
            /*allergenBd['enabled'] === 1 && */
            if(aux === undefined){
                //selectedAndUnselected.push(allergenBd['id_allergen']);
                //enableds.push(0);
                toDelete.push(foodBd['id_food']);
            }
        }
        console.log(toDelete);
        console.log(toAdd);

        let send = {toAdd, toDelete};

        this._eventService.updateEventFood(this.event.id_event, send).subscribe(
            response => {
                console.log(response);
                //this._router.navigate(['eventDetail/',this.event.id_event]);
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
        console.log(this.event.id_event);
        this._foodService.getFoods().subscribe((foods: Food[]) => {
            console.log(foods);
            this.foods = foods;
            let controls = [];
            console.log('hola');
            for(let food of this.foods) {
                console.log(food);

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
        console.log(this.staffEvent);
        console.log(this.staffForm.value.staff);
        const selectedStaffIds = this.staffForm.value.staff
            .map((v, i) => v ? this.staff[i].id_staff : null)
            .filter(v => v !== null);

        console.log(selectedStaffIds);

        let toDelete = [];
        let toAdd = [];
        selectedStaffIds.forEach((item, index) => {
            console.log(item);
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.staffEvent.find(function (elem){
                return elem['id_staff'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){
                //console.log('no existe en foodallergens');
                //selectedAndUnselected.push(item);
                //enableds.push(1);
                toAdd.push(item);
            }
        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let staffBd of this.staffEvent){
            let aux = selectedStaffIds.find(function (elem){
                //console.log(staffBd['id_staff']);
                return elem === staffBd['id_staff'];
            });
            console.log(staffBd);
            /*allergenBd['enabled'] === 1 && */
            if(aux === undefined){
                //selectedAndUnselected.push(allergenBd['id_allergen']);
                //enableds.push(0);
                toDelete.push(staffBd['id_staff']);
            }
        }
        console.log(toDelete);
        console.log(toAdd);

        let send = {toAdd, toDelete};

        this._eventService.updateEventStaff(this.event.id_event, send).subscribe(
            response => {
                //console.log(response);
                this.staffEdited = 'success';
                //this._router.navigate(['eventDetail/',this.event.id_event]);
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
            console.log(staff);
            this.staff = staff;
            let controls = [];
            console.log('hola staff');
            for(let person of this.staff) {
                console.log(person);

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

}
