import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FoodService } from '../../services/food.service';
import { Food } from '../../models/food';
import { UserService } from '../../services/user.service';
import { AllergenService } from '../../services/allergen.service';
import { Allergen } from "../../models/allergen";
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';


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

  constructor(
      private _formBuilder: FormBuilder,
      private _route: ActivatedRoute,
      private _router: Router,
      private _foodService: FoodService,
      private _userService: UserService,
      private _allergenService: AllergenService
  ) {
      this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
      if(this.identity == null){
          this._router.navigate(["/login"]);
      }
    this._route.params.subscribe(params => {
      //console.log(params);
      let id_food = params['id'];
      this.getFood(id_food);
    });
  }

  getFood(id_food){
      this._foodService.viewFood(id_food).subscribe(
          response => {
            //console.log(response);

            //console.log('estoy dentro');
            this.food = response;
            if(isUndefined(this.food)){
                this._router.navigate(['allFoods']);

            }
            //console.log(this.food.allergens);
            //console.log(this.food.allergens[0]['name_allergen']);
            this.allergensFood = this.food.allergens;
            this.page_title = 'Editar ' + this.food.title;
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
    //Servicio
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
      //Rcojo los alergenos seleccionados
        console.log(this.allergensFood);
      console.log(this.allergensForm.value.allergens);
        const selectedAllergenIds = this.allergensForm.value.allergens
            .map((v, i) => v ? this.allergens[i].id_allergen : null)
            .filter(v => v !== null);

        console.log(selectedAllergenIds);

        let selectedAndUnselected = [];
        let enableds = [];
        selectedAllergenIds.forEach((item, index) => {
            //console.log(item);
            //Recorro los alergenos almacenados en BD
            //Busco para cada alérgeno de la BD si existe en los seleccionados en el form
            let aux = this.allergensFood.find(function (elem){
                return elem['id_allergen'] === item;
            });
            //Si aux es undefined, el alergeno no existe en BD, SE AÑADE
            if(aux === undefined){
                //console.log('no existe en foodallergens');
                selectedAndUnselected.push(item);
                enableds.push(1);
            }
            //Si aux es != undefined, el alergeno ya está en la BD pero deshabilitado,
            //hay que habilitarlo
            if(aux !== undefined && aux['enabled']==0){
                //console.log(aux);
                //console.log('existe en foodAllergens');
                selectedAndUnselected.push(aux['id_allergen']);
                enableds.push(1);
            }
        });
        //Ahora hay que mirar que esté en la BD pero no en los seleccionados,
        //para poder deshabilitarlo
        for(let allergenBd of this.allergensFood){
            let aux = selectedAllergenIds.find(function (elem){
                return elem === allergenBd['id_allergen'];
            });
            if(allergenBd['enabled'] === 1 && aux === undefined){
                selectedAndUnselected.push(allergenBd['id_allergen']);
                enableds.push(0);
            }
        }
        console.log(selectedAndUnselected);
        console.log(enableds);


        this._foodService.updateFoodAllergens(this.food.id_food, selectedAndUnselected, enableds).subscribe(
            response => {
                console.log(response);
                this._router.navigate(['foodDetail/',this.food.id_food]);
            },
            error => {
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
                console.log(allergen['id_allergen']);
                if((this.allergensFood.find(aux => aux['id_allergen'] === allergen['id_allergen'] && aux['enabled'] === 1 )) != undefined){
                    console.log('hola');
                    controls.push(new FormControl(true));
                }else{
                    console.log('adios');
                    controls.push(new FormControl(false));
                }
            }

            this.allergensForm = this._formBuilder.group({
                allergens: new FormArray(controls)
            });
        });
    }

}




