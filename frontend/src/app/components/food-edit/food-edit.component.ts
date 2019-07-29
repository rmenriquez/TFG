import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';
import {TranslateService} from '@ngx-translate/core';

import { FoodService } from '../../services/food.service';
import { Food } from '../../models/food';

import { UserService } from '../../services/user.service';

import { AllergenService } from '../../services/allergen.service';
import { Allergen } from "../../models/allergen";


import { isUndefined } from "util";

@Component({
  selector: 'app-food-edit',
  templateUrl: './food-edit.component.html',
  providers: [UserService, FoodService, AllergenService]
})
export class FoodEditComponent implements OnInit {

  private page_title: string;
  private status: string;
  private identity;

  public food: Food;

  public errors: {};



  allergensFood: any[];

    allergensForm: FormGroup;
    allergens: Allergen[];
    allergensEdited;

  constructor(
      private _formBuilder: FormBuilder,
      private _route: ActivatedRoute,
      private _router: Router,
      private _foodService: FoodService,
      private _userService: UserService,
      private _allergenService: AllergenService,
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
      let id_food = params['id'];
      this.getFood(id_food);
    });
  }

  getFood(id_food){
      this._foodService.viewFood(id_food).subscribe(
          response => {
            this.food = response;
            if(isUndefined(this.food)){
                this._router.navigate(['allFoods']);

            }
            this.allergensFood = this.food.allergens;
            this.page_title =  this.food.title;
            this.getAllergens();
          },
          error => {
            console.log(<any> error);
            this.errors = error.error;
            this._router.navigate(['allFoods']);
          }
      );
  }

  onSubmit(form){
    console.log(this.food.id_food);
    this._foodService.updateFood(this.food, this.food.id_food).subscribe(
        response => {
          if(response == null){
            this.status = 'success';
            this._router.navigate(['/allFoods']);
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
    onSubmitAllergens(form){
      //Recojo los alergenos seleccionados
        console.log(this.allergensFood);
      console.log(this.allergensForm.value.allergens);
        const selectedAllergenIds = this.allergensForm.value.allergens
            .map((v, i) => v ? this.allergens[i].id_allergen : null)
            .filter(v => v !== null);

        let toDelete = [];
        let toAdd = [];
        selectedAllergenIds.forEach((item, index) => {
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.allergensFood.find(function (elem){
                return elem['id_allergen'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){
                toAdd.push(item);
            }

        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let allergenBd of this.allergensFood){
            let aux = selectedAllergenIds.find(function (elem){
                return elem === allergenBd['id_allergen'];
            });
            if(aux === undefined){
                toDelete.push(allergenBd['id_allergen']);
            }
        }



        this._foodService.updateFoodAllergens(this.food.id_food, toAdd, toDelete).subscribe(
            response => {
                console.log(response);
                this.allergensEdited = 'success';
            },
            error => {
                this.allergensEdited = 'error';

                console.log(<any> error);
                this.errors = error.error;
            }
        );
    }

    getAllergens(){
        this._allergenService.getAllergens().subscribe(allergens => {
            this.allergens = allergens;
            let controls = [];

            for(let allergen of this.allergens){
                if((this.allergensFood.find(aux => aux['id_allergen'] === allergen['id_allergen'] )) != undefined){
                    controls.push(new FormControl(true));
                }else{
                    controls.push(new FormControl(false));
                }
            }

            this.allergensForm = this._formBuilder.group({
                allergens: new FormArray(controls)
            });
        });
    }

    useLanguage(language: string) {
        this._translate.use(language);
    }

}




