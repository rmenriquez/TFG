import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, FormArray } from '@angular/forms';
import {TranslateService} from '@ngx-translate/core';

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
      this._allergenService.getAllergens().subscribe(allergens => {
          this.allergens = allergens;
          const controls = this.allergens.map(c => new FormControl(false));

          this.allergensForm = this._formBuilder.group({
              allergens: new FormArray(controls)
          });
      });

  }

  onSubmitFood(form){
    this._foodService.createFood(form.value).subscribe(
        response=>{
          this.status = 'success';
          this.food = response;
          let id = response['id_food'];
          this._foodService.setFood(response);

        },
        error=>{
          this.status = 'error';
          this.errors = error.error;
        }
    );
  }

  onSubmitAllergens(form){
      const selectedAllergenIds = this.allergensForm.value.allergens
          .map((v, i) => v ? this.allergens[i].id_allergen : null)
          .filter(v => v !== null);

      this._foodService.setFoodAllergens(this.food.id_food, selectedAllergenIds).subscribe(
          response => {
              this._router.navigate(['foodDetail/',this.food.id_food]);
          },
          error => {
              this.errors = error.error;
          }
      );
  }

    useLanguage(language: string) {
        this._translate.use(language);
    }
}
