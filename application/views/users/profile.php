<!-- Breadcrumb -->

<section class="breadcrumb">
	<h1><?= $title ?></h1>
	<ul>
		<li><a href="#">Pages</a></li>
		<li class="divider la la-arrow-right"></li>
		<li><a href="#">Blog</a></li>
		<li class="divider la la-arrow-right"></li>
		<li>Add Post</li>
	</ul>
</section>

<div class="mb-2">
	<?php echo validation_errors(); ?>
</div>

<?php echo form_open_multipart('users/profile')?>

<div class="grid lg:grid-cols-4 gap-5">

	<!-- Content -->
	<?php
	$user_array  = $this->session->userdata('user_id');
	?>
	<input type="hidden" name="user_id" value="<?php echo $user_array['id']; ?>">

	<div class="flex flex-col gap-y-5 lg:col-span-2 xl:col-span-1">

		<!-- Featured Image -->
		<div class="card p-5 justify-center">
			<h3>Profile Picture</h3>
			<img style="text-align: center;" class="image items-center center-block justify-center" src="<?php echo base_url() . 'assets/uploads/users/' . $user['profile_image'] ?>" alt="">
			<input class="block
				w-full
				px-3
				py-1.5
				text-base
				font-normal
				text-gray-700
				bg-white bg-clip-padding
				border border-solid border-gray-300
				rounded
				transition
				ease-in-out
				m-0
				focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none rounded uppercase"
				   type="file" name="userfile" size="20">
		</div>
	</div>



	<div class="lg:col-span-2 xl:col-span-3">
		<div class="card p-5">





			<div class="row form_field_outer_row">
				<div class="grid lg:grid-cols-4 gap-5">

					<div class="lg:col-span-1 xl:col-span-1">
						<label for="ice-cream-choice">Name:</label>
						<input type="text" class="form-control w_90" name="name" id="" value="<?php echo $user['name'] ?>" placeholder="Your name." />
					</div>

					<div class="lg:col-span-1 xl:col-span-1">
						<label for="ice-cream-choice">UserName:</label>
						<input type="text" class="form-control w_90" name="username" value="<?php echo $user['username'] ?>" id="" placeholder="This will be displayed to other users." />
					</div>

					<div class="lg:col-span-1 xl:col-span-1">
						<label for="ice-cream-choice">Email:</label>
						<input type="text" class="form-control w_90" name="email" id="" value="<?php echo $user['email'] ?>" placeholder="Email is wont be displayed to other users." />
					</div>


					<div class="lg:col-span-1 xl:col-span-1">
						<label for="ice-cream-choice">Phone:</label>
						<input type="text" class="form-control w_90" name="phone" id="" value="<?php echo $user['phone'] ?>" placeholder="Phone is wont be displayed to other users." />
					</div>


					<div class="lg:col-span-2 xl:col-span-2">
						<label for="ice-cream-choice">Password:</label>
						<label class="form-control-addon-within">
							<input id="password" type="password" name="password" class="form-control border-none" value="12345">
							<span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
									class="btn btn-link text-gray-300 dark:text-gray-700 la la-eye text-xl leading-none"
									data-toggle="password-visibility"></button>
                        </span>
						</label>
					</div>


					<div class="lg:col-span-2 xl:col-span-2">
						<label for="ice-cream-choice">Password Confirm:</label>
						<label class="form-control-addon-within">
							<input id="password" type="password" name="password2" class="form-control border-none" value="12345">
							<span class="flex items-center ltr:pr-4 rtl:pl-4">
                            <button type="button"
									class="btn btn-link text-gray-300 dark:text-gray-700 la la-eye text-xl leading-none"
									data-toggle="password-visibility"></button>
                        </span>
						</label>
					</div>



				</div>


				<div class="mb-5">
					<label class="label block mb-2" for="content">Bio</label>
					<textarea id="content" name="bio" class="form-control" rows="16"></textarea>
				</div>

				<div class="mb-5">
					<button type="submit" class="btn btn_primary ltr:ml-auto rtl:mr-auto uppercase">Update Profile</button>
				</div>

			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>


