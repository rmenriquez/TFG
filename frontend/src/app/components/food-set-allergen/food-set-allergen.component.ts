/**
 * Created by RaquelMarcos on 10/7/19.
 */
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';
import { AllergenService } from '../../services/allergen.service';
import {isUndefined} from "util";
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';
import {HttpClient} from "@angular/common/http";
import {Allergen} from "../../models/allergen";


@Component({
    selector: 'app-food-set-allergen',
    templateUrl: './food-set-allergen.component.html',
    providers: [UserService, FoodService]
})

export class FoodSetAllergen implements OnInit{
    private page_title: string;
    private identity;
    private id_food;
    public food: Food;

    allergensForm: FormGroup;
    allergens: Allergen[];
    /*    {
            "id_allergen": 1,
            "name_allergen": "Gluten"
        },
        {
            "id_allergen": 2,
            "name_allergen": "Crustáceos y productos a base de crustáceos"
        },
        {
            "id_allergen": 3,
            "name_allergen": "Huevos y productos a base de huevos"
        },
        {
            "id_allergen": 4,
            "name_allergen": "Pescados y productos a base de pescados"
        },
        {
            "id_allergen": 5,
            "name_allergen": "Cacahuetes y productos a base de cacahuetes"
        },
        {
            "id_allergen": 6,
            "name_allergen": "Soja y productos a base de soja"
        },
        {
            "id_allergen": 7,
            "name_allergen": "Leche y sus derivados"
        },
        {
            "id_allergen": 8,
            "name_allergen": "Frutos de cáscara"
        },
        {
            "id_allergen": 9,
            "name_allergen": "Apio y productos derivados"
        },
        {
            "id_allergen": 10,
            "name_allergen": "Mostaza y productos derivados"
        },
        {
            "id_allergen": 11,
            "name_allergen": "Granos de sésamo y productos a base de granos de sésamo"
        },
        {
            "id_allergen": 12,
            "name_allergen": "Dióxido de azufre y sulfitos"
        },
        {
            "id_allergen": 13,
            "name_allergen": "Altramuces y productos a base de altramuces"
        },
        {
            "id_allergen": 14,
            "name_allergen": "Moluscos y productos a base de moluscos"
        }
    ];*/

    private errors = {};

    constructor(
        private _formBuilder: FormBuilder,
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private _foodService: FoodService,
        private _allergenService: AllergenService
    ){
        this.identity = this._userService.getIdentity();
    }

    ngOnInit(){
        if(this.identity == null){
            this._router.navigate(["/login"]);
        }

        this._allergenService.getAllergens().subscribe(allergens => {
            this.allergens = allergens;
            const controls = this.allergens.map(c => new FormControl(false));

            this.allergensForm = this._formBuilder.group({
                allergens: new FormArray(controls)
            });
        });

        this._route.params.subscribe(params => {
            console.log(params);
            this.id_food = params['id'];
            this.getFood(this.id_food);
        });
    }

    onSubmit(){
        const selectedAllergenIds = this.allergensForm.value.allergens
            .map((v, i) => v ? this.allergens[i].id_allergen : null)
            .filter(v => v !== null);

        console.log(selectedAllergenIds);
        this._foodService.setFoodAllergens(this.id_food, selectedAllergenIds).subscribe(
            response => {
                console.log(response);
                this._router.navigate(['foodDetail/',this.id_food]);
            },
            error => {
                console.log(<any> error);
                this.errors = error.error;
            }
        );
    }

   getFood(id_food){
       this._foodService.viewFood(id_food).subscribe(
           response => {
               console.log(response);
               this.food = response;
               this.page_title = 'Set allergens to ' + this.food.title;
               if(isUndefined(this.food)){
                   this._router.navigate(['allFoods']);
               }
       },
           error => {
               console.log(<any> error);
           }
       );
    ;
   }



}