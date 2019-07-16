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
              this._allergenService.getAllergens().subscribe(allergens => {
                  this.allergens = allergens;
                  //console.log(allergens);
                  //console.log(this.allergens);
                  //console.log(this.food);
                  console.log(this.allergensFood);

                  console.log(this.allergensFood[0]['id_allergen']);
                  let i = 0;
                  let controls = this.allergens.map(function (allergen) {
                      console.log(allergen);
                      /**
                       * Al poner un for o un if ya no recorre this.allergens.
                       * No puede acceder a this.allergensFood: dice que
                       * Potentially invalid usage of this
                       * */
                        /*if(allergen['id_allergen'] === this.allergensFood[i]['id_allergen']
                        && this.allergensFood[i]['enabled'] === 1){
                            i++;
                              new FormControl(true);
                        }else{
                            i++;
                            new FormControl(false);
                        }*/
                      for(let i = 0; i < this.allergensFood.length; i++){
                       console.log(this.allergensFood[i]);
                           if(allergen['id_allergen'] === this.allergensFood[i]['id_allergen']
                           && this.allergensFood[i]['enabled']){
                                console.log('hola');
                                new FormControl(true);
                           }else {
                               console.log('adios');
                                new FormControl(false);
                           }
                       }
                  });
                  this.allergensForm = this._formBuilder.group({
                      allergens: new FormArray(controls)
                  });



              });

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
        const selectedAllergenIds = this.allergensForm.value.allergens
            .map((v, i) => v ? this.allergens[i].id_allergen : null)
            .filter(v => v !== null);

        console.log(selectedAllergenIds);
        this._foodService.setFoodAllergens(this.food.id_food, selectedAllergenIds).subscribe(
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


}
