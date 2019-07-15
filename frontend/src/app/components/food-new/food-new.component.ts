import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';

import { UserService } from '../../services/user.service';
import { Food } from '../../models/food';
import { FoodService } from '../../services/food.service';
import { AllergenService } from '../../services/allergen.service';
import {Allergen} from "../../models/allergen";

@Component({
  selector: 'app-food-new',
  templateUrl: './food-new.component.html',
  providers: [UserService, FoodService, AllergenService]
})
export class FoodNewComponent implements OnInit {
  private page_title: string;
  private identity;
  public food: Food;
  public status: string;

  public errors = {};

  allergensForm: FormGroup;
   allergens: Allergen[];

  constructor(
      private _formBuilder: FormBuilder,
      private _route: ActivatedRoute,
      private _router: Router,
      private _userService: UserService,
      private _foodService: FoodService,
      private _allergenService: AllergenService
  ) {
      this.page_title = 'Create new food';
      this.identity = this._userService.getIdentity();
  }

  ngOnInit() {
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

  }

  onSubmitFood(form){
    //console.log(this._userService.getIdentity());
    //console.log(this.food);
    //this.food.restaurant = this._userService.identity.id_user;
    console.log(form.value);
    this._foodService.createFood(form.value).subscribe(
        response=>{
          console.log(response);
          this.status = 'success';
          this.food = response;
          let id = response['id_food'];
          console.log(id);
          this._foodService.setFood(response);
          //this._router.navigate(['/foodSetAllergens/',id]);

        },
        error=>{
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
