<div class="container flex items-center justify-center mt-20 py-10">
	<div class="w-full md:w-1/2 xl:w-1/3">
		<div class="mx-5 md:mx-10">
			<h2 class="uppercase">It’s Great To See You!</h2>
			<h4 class="uppercase">Login Here</h4>
		</div>
		<div class="card mt-5 p-5 md:p-10">

			<?php echo form_open('users/login') ?>
				<div class="mb-5">
					<label class="label block mb-2" for="email">UserName</label>
					<input id="email" type="text" name="username" class="form-control" placeholder="example@example.com">
				</div>
				<div class="mb-5">
					<label class="label block mb-2" for="password">Password</label>
					<label class="form-control-addon-within">
						<input id="password" type="password" name="password" class="form-control border-none" value="12345">
						<span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
									class="btn btn-link text-gray-300 dark:text-gray-700 la la-eye text-xl leading-none"
									data-toggle="password-visibility"></button>
                        </span>
					</label>
				</div>
				<div class="flex items-center">
					<a href="auth-forgot-password.html" class="text-sm uppercase">Forgot Password?</a>
					<button type="submit" class="btn btn_primary ltr:ml-auto rtl:mr-auto uppercase">Login</button>
				</div>
			</form>

		</div>
	</div>
</div>
